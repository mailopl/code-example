<?php 
use \Codeception\Module\Db;

$I = new ApiGuy($scenario);
$I->wantTo('get free record (API user )');
$I->sendGET('/api/test-repository/50c8770fff1d832811000000');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

//$I->amHttpAuthenticated('davert','123456');
//$I->haveHttpHeader('Content-Type','application/x-www-form-urlencoded');

//$I->seeResponseContainsJson(array(array('model' => 'E39','year' => "2003")));