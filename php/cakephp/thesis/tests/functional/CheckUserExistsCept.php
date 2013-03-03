<?php
$I = new TestGuy($scenario);
$I->seeInDatabase('users', array('email' => 'wawrzyniak.mm@gmail.com'));