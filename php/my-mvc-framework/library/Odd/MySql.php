<?php
function str_replace_once($search, $replace, $subject){
	if( ($pos = strpos($subject, $search)) !== false ){
		$ret = substr($subject, 0, $pos).$replace.substr($subject, $pos + strlen($search));
		return $ret;
	}
	return $subject;
}
interface DBConnection {
  public function prepare($q); /* prepares query */
  public function exec($q); /* wyrzuca zapytanie i zwraca ilosc wierszy */
  public function query($q); /* wyrzuca zapytanie i zwraca wiersze */
  public function errorCode(); /* zwraca kod bledu ostatniego zapytania */
  public function errorMsg(); /* zwraca nazwe bledu ostatniego zapytania */
}

interface DBStatement {
  public function execute(); /* wykonaj zapytanie zwraca affectedRows()*/
  public function bindParam($param, $val); /* zbinduj parametr do wartosci */
  public function bindColumn($column, $var); /* zbinduj kolumne do zmiennej */
  public function fetch(); /* zwraca wiersz i zapisuje wiesz do zbindowanych kolumn */
  public function fetchAll(); /* zwraca wszystkie wiersze i zapisuje je do zbindowanych kolumn */
  public function closeCursor(); /* umozliwia uzycie zapytania drugi raz */
  public function rowCount(); /* zwraca ilosc wierszy wyniku */
  public function affectedRows(); /* zwraca ilosc wierszy wydymanych przez zapytanie */
  public function errorCode(); /* zwraca kod bledu ostatniego zapytania */
  public function errorMsg(); /* zwraca nazwe bledu ostatniego zapytania */
}

/**
 * Database wrapper class.
 */
class MySql implements DBConnection {
	private $link;
	 /**
     * Private statement.
     * @var MySqlStatement
     */
	private $stmt;
	 /**
     * Constructor. Creates (pernament) connection. Throws MysqlException.
     * Takes 5 arguments, 4 of them are mandatory: username, password, host, database name, use_pernament_connection? .
     * @return void
     */
	public function __construct($username, $password, $host, $dbname, $permament=false) {
		if (!$permament) {
            $this->link = @mysql_connect($host, $username, $password);
		} else {
			$this->link = @mysql_pconnect($host, $username, $password);
		}
		if (!$this->link) {
			throw new MysqlException("Connection", 666);
		}
		if (!mysql_select_db($dbname, $this->link)) {
			throw new MySqlException($this->errorMsg(), $this->errorCode());
		}
		$this->stmt = false;
	}
    public function __call($funct, $params){
        global $_REQUEST;
        global $GLOBALS;
        extract($GLOBALS , EXTR_REFS);

        if (strripos($funct, 'insertTo') !== false){
            $values = explode(',',$params[0]);
            $table = strtolower(substr($funct, 8, strlen($funct)-8));
            $q = "INSERT INTO `$table`(%s) VALUES(%s);";
            $q1 = $q2 = "";

            foreach($values as $val){
                $aVal = trim($val);
                $q1 = $q1 . '`'.$aVal . '`, ';
                $q2 = $q2 . "'" . mysql_real_escape_string($GLOBALS[$aVal]) . "', ";

            }

            $q = sprintf($q, substr($q1, 0, strlen($q1)-2), substr($q2,0,strlen($q2)-2));
            $this->stmt = new MySqlStatement($q, $this->link);
		    $this->stmt->execute();
            return $this->stmt->affectedRows();
      }
      }
	 /**
     * Prepare query.
     * @return MySqlStatement
     */
	public function prepare($q) {
		return new MySqlStatement($q, $this->link);
	}
	public function exec($q) {
		$this->stmt = new MySqlStatement($q, $this->link);
		return $this->stmt->execute();
	}
	 /**
     * Execute query and return fetched data.
     * @return array
     */

	public function query($q) {
		$this->stmt = new MySqlStatement($q, $this->link);
		$this->stmt->execute();
		return $this->stmt->fetchAll();
	}
    
	 /**
     * Prepare, bind, process query. 3 in one, to make things faster.
     * @return array / int
     */
    public function proc($q) {

		if ( func_num_args() > 1){

			$questionMarks = substr_count($q, '?');
			if ( $questionMarks < (func_num_args()-1) ){
				throw new MysqlException('Bad question marks count.' . $questionMarks .', should be '. func_num_args()-1);
			}

            $q = str_replace('?', '[-?-]', $q); #

			$arrayobject = new ArrayObject(func_get_args());
			$iterator = $arrayobject->getIterator();
			$iterator->next();

 			while($iterator->valid()) {
                if (is_int($iterator->current())){
                    $q = str_replace_once("[-?-]", $iterator->current() , $q);
                }else{
                    $q = str_replace_once("[-?-]", "'". (ctype_alnum($iterator->current()) ? mysql_real_escape_string($iterator->current()) : $iterator->current()) . "'" , $q);
                }
    			$iterator->next();
 			}
		}
		$this->stmt = new MySqlStatement($q, $this->link);
		$this->stmt->execute();

		if (stristr($q, "select")){
				$data = $this->stmt->fetchAll();
				/**
				 * global Stripslashes mod for select statements.
				 */
				/*if ($data){
					foreach ($data as $key => &$val){
						foreach ($val as $a => &$b){
							$val[$a] = stripslashes($b);
						}
					}
				}*/
				return $data;
		}else{ //for UPDATE, DELETE, INSERT, ETC.
				return $this->stmt->affectedRows();
		}
	}
	public function count($table, $condition){
		$count = $this->proc("SELECT COUNT(*) FROM $table " .($condition ? "WHERE $condition" : null));
		return $count[0][0];
	}
	public function errorCode() {
		return mysql_errno($this->link);
	}
	public function errorMsg() {
		return mysql_error($this->link);
	}
	/**
	 * Selects database, given in first argument.
	 * @return bool
	 */
	public function selectDB($dbname){
		 return mysql_select_db($dbname, $this->link);

	}
	function __destruct() {
		mysql_close($this->link);
	}
}
function getmicrotime(){
list($usec, $sec) = explode(" ",microtime());
return ((float)$usec + (float)$sec);
}

class MySqlStatement implements DBStatement {
	private $link;
	private $query;
	private $boundParams;
	private $boundColumns;
	private $result;
	private $executed;
	 /**
     * Never called by us.
     * @return void
     */
	public function __construct($q, $link) {
		$this->link = $link;
		$this->query = $q;
		$this->boundParams = array();
		$this->boundColumns = array();
		$this->executed = false;
	}
	 /**
     * Execute prepared query, bind params and make them safe. SQJ-Injection safe.
     * @return int
     */
	public function execute() {
		$query = $this->query;
		foreach (new ArrayObject($this->boundParams) as $bind => $param) {
			$query=str_replace($bind, '\'' . mysql_real_escape_string($param) . '\'', $query);
		}

		global $GLOBALS;


        $time_start = getmicrotime();
		    $this->result = mysql_query($query, $this->link);
        $time_end = getmicrotime();
        if (!empty($query)){
          $GLOBALS['totalExecutionTime'] += ($time_end - $time_start);
        $GLOBALS['debug'][] = array('query' => $query, 'time'=>$time_end - $time_start.' s');
        }

		if (!$this->result) {
			throw new MySqlException($this->errorMsg(), $this->errorCode());
		} else {
			$this->executed = true;
		}
		return $this->affectedRows();
	}
	public function bindParam($param, $val) {
		$this->boundParams["${param}"] = $val;
	}
	public function bindColumn($column, $val) {
		$this->boundColumns["${column}"] = &$val;
	}
	public function closeCursor() {
		$this->executed = false;
	}
	 /**
     * If no query was executed, script is going to crash. Ratherly used function.
     * @return void
     */
	private function assumeExecuted() {
		if (!$this->executed) {
			throw new Exception("First execute " .$this->query);
		}
	}
	 /**
     * Return rows count, touched by INSERT, DELETE, UPDATE etc.
     * @return int
     */
	public function affectedRows() {
		$this->assumeExecuted();
		return mysql_affected_rows($this->link);
	}
	/**
    * Return row count, after using select.
    * @return int
    */
	public function rowCount() {
		$this->assumeExecuted();
		return mysql_num_rows($this->result);
	}
	public function errorCode() {
		return mysql_errno();
	}
	public function errorMsg() {
		return mysql_error();
	}
	 /**
     * Returns one row after SELECT and binds it to variables.
     * @return array
     */
	public function fetch() {
		$this->assumeExecuted();
		$data = mysql_fetch_array($this->result, MYSQL_BOTH);
		if (!$data) {
			return $data;
		}
		$obj = new ArrayObject($this->boundColumns);

		foreach ($obj as $column => &$variable) {
			if (isset($data["${column}"])) {
				$variable = $data["${column}"];
			}
		}
		return $data;
	}
	 /**
     * Returns EVERY row after SELECT and binds it up to saved variables.
     * @return array
     */
	public function fetchAll() {
		$this->assumeExecuted();
		$ret = false;

		while ($data = mysql_fetch_array($this->result, MYSQL_BOTH)) {
			/*foreach (new ArrayObject($this->boundColumns) as $column => $variable) {
				if (isset($data["${column}"])) {
					$variable = $data["${column}"];
				}
			}*/
			/**
			 * GLOBAL STRIPSLASHES MOD!
			 */
			foreach ($data as $key => &$v){
				$data[$key] = stripslashes($v);
			}
			$ret[] = $data;
		}
		return $ret;
	}
}

