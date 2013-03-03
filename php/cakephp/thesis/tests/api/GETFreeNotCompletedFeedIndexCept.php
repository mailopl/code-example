<?php 
use \Codeception\Module\Db;

$I = new ApiGuy($scenario);
$I->wantTo('get free records from not completed repo (API user )');
$I->sendGET('/api/test-repository-free-nc');
$I->seeResponseCodeIs(403);
$I->seeResponseIsJson();