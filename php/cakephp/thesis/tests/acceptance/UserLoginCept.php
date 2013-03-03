<?php
$I = new WebGuy($scenario);
$I->wantTo('login');
$I->amOnPage('/users/login');
$I->seeInCurrentUrl('/users/login');

$I->see('Email');
$I->see('Password');
$I->submitForm('#UserLoginForm', array('data' => array(
	'User' => array(
    	'password' => 'asdefx',
    	'email' => 'mandms@gmail.com'
	)
)));
$I->seeInCurrentUrl('feeds/my');