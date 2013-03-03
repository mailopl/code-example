<?php 
use \Codeception\Module\Db;

$I = new ApiGuy($scenario);
$I->wantTo('get free records (API user )');
$I->sendGET('/api/test-repository');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

//$I->amHttpAuthenticated('davert','123456');
//$I->haveHttpHeader('Content-Type','application/x-www-form-urlencoded');

//$I->seeResponseContainsJson(array(array('model' => 'E39','year' => "2003")));