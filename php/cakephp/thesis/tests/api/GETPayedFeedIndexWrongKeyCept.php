<?php 
use \Codeception\Module\Db;

$I = new ApiGuy($scenario);
$I->wantTo('get paid records with wrong key (API user)');
$I->sendGET('/api/test-repository-paid?key=401');
$I->seeResponseCodeIs(403);
