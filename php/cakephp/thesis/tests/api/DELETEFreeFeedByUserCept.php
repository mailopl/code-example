<?php 
use \Codeception\Module\Db;

$I = new ApiGuy($scenario);
$I->wantTo('delete some row as API user');
$I->sendDELETE('/api/my-free-repository/50c62db1ff1d83e806000001/');
$I->seeResponseCodeIs(401);

