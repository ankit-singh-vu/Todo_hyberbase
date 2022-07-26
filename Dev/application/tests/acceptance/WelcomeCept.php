<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure access login page');
$I->amOnPage('/');
$I->see('HyperBase 3.0');