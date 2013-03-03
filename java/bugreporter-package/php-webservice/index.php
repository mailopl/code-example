<?php
// slim framework init
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->getLog()->setEnabled(true);

// log format
$logFormat = 'DATE: %s; VERSION %s; EXCEPTION %s; PLACE: %s:%s; PARAMS: %s;';

// username, password
$username = "user";
$password = "pass";

//license keys
$keys = explode("\n", file_get_contents('keys.data'));


$app->post('/submit', function () use ($app, $logFormat, $username, $password, $keys) {
    
    /**
    * Check authorization data
    */
    if (isset($_POST['auth'])){
        if ($_POST['auth']['username'] != $username || $_POST['auth']['password'] != $password){
            die('401 Unauthorized');
        }
    }
    
    /**
    * Check license information
    */
    if (isset($_POST['param']['license'])){
        if (!in_array($_POST['param']['license'], $keys)){
            die('401 Unauthorized license: ' . $_POST['param']['license']);
        }
    }
    
    $logParams = "";
    if (isset($_POST['param'])){
        foreach($_POST['param'] as $param => $value){
            $logParams .= $param . '.' . $value . '|';
        }
    }
    
    $out = sprintf(
        $logFormat, 
        $_POST['timestamp'],
        $_POST['version'], 
        $_POST['exception'], 
        $_POST['class'],
        $_POST['line'],
        $logParams
    );
    $out .= "\n\n\n";
    
    file_put_contents(
        'log.txt', 
        $out,
       FILE_APPEND
    );
    
    die('200 OK');
});

$app->get('/reports', function () use($logFormat) {
    
    //zwroc liste raportow
    $errors = explode("\n\n\n", file_get_contents("log.txt"));
    $data = array();
    
    //przerob plik na wygodna tablice
    foreach($errors as $error){
        preg_match('/' . sprintf($logFormat, '(.*)','(.*)','(.*)','(.*)','(.*)','(.*)').'/sm', $error, $matches);
      
        @list($all, $date, $ver, $msg, $file, $line, $params) = $matches;
        
        $paramLines = explode("|", $params);
        $params = array();
        
        
        foreach($paramLines as $paramStr){
            if (empty($paramStr)) continue;
            $tmp = explode(".", $paramStr);
            $params[$tmp[0]] = $tmp[1]; 
        }
        
        $data[] = array(
            'date' => $date,
            'version'   => $ver,
            'msg'       => $msg,
            'file'      => $file,
            'line'      => $line,
            'params'    => $params
        );
    }
    ?>
    <ul>
        <?php foreach($data as $item): ?>
            <?php if(empty($item['msg'])) continue ?>
            <li>
                Date: <?php echo $item['date'] ?>, 
                Version: <?php echo $item['version'] ?> <br />
                <b>Additional parameters:</b> <br />
                <ul>
                    <?php foreach($item['params'] as $key => $value): ?>
                        <li><?php echo $key ?> : <?php echo $value ?></li>
                    <?php endforeach ?>
                </ul>
                <br />
                
                <?php echo $item['file'] ?>@<?php echo $item['line'] ?>: <br />
                <pre><?php echo nl2br($item['msg']) ?></pre>
            </li>        
        <?php endforeach; ?>
    </ul>
<?php 
});
$app->run();
