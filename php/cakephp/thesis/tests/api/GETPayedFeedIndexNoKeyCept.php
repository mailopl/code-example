<?php 
use \Codeception\Module\Db;

$I = new ApiGuy($scenario);
$I->wantTo('get paid with no key records (API user)');
$I->sendGET('/api/test-repository-paid');
$I->seeResponseCodeIs(401);
