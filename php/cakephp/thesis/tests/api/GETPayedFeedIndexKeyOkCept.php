<?php 
use \Codeception\Module\Db;

$I = new ApiGuy($scenario);
$I->wantTo('get paid records with key ok (API user)');
$I->sendGET('/api/test-repository-paid?key=6b756d1b177f17bbb386a110867e1076660a25b1');
$I->seeResponseCodeIs(200);
