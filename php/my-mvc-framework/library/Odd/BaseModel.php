<?php
class BaseModel extends Database{
    protected $_table;
    private $_fields;
    private $_ownQuery;
    private $_orderBy = 'id desc';
    private $_limit = 150;
    private $_offset;
    protected $_db;
    private $_where;

    public function __construct(){
      $this->_db = Registry::getRegistry('db');

    }
    public function fetchAll(){
      $q = "SELECT * FROM `" . $this->_table ."` " . ($this->_where ? 'WHERE ' . $this->_where : null)." ORDER BY " . $this->_orderBy ." LIMIT " . $this->_limit;
      return $this->_db->proc($q);
    }
    public function fetchRow(){
      $q = "SELECT * FROM `" . $this->_table ."` " . ($this->_where ? 'WHERE ' . $this->_where : null)." LIMIT 1" ;
      $data = $this->_db->proc($q);
      return $data[0];
    }
    public function setLimit($limit){
        $this->_limit = $limit;
        return $this;
    }
    public function setSort($sort){
      $this->_orderBy = $sort;
      return $this;
    }
    public function setWhere($where){
        $this->_where = $where;
      return $this;
    }
};