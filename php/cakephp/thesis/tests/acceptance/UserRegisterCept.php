<?php
$I = new WebGuy($scenario);
$I->wantTo('register');
$I->amOnPage('/users/register');
$I->seeInCurrentUrl('/users/register');

$I->see('Register');
$I->see('Password');
//$I->fillField('data[User][email]', 'wawrzyniak.mm@gmail.com');
//$I->fillField('data[User][password]', '123');
//$I->press('Register');

$email = 'test_'.rand(9, 1000) . 'user@gmail.com';
$I->submitForm('#UserRegisterForm', array('data' => array(
	'User' => array(
    	'password' => 'asdefx',
    	'email' => $email
	)
)));
$I->seeInCurrentUrl('feeds/my'); 