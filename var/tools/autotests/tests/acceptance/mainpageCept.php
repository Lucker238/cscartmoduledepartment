<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');


$I->amOnPage('/');
$I->Click('Мой профиль');
$I->Click('Войти');
$I->fillField(['id' => 'login_main_login'] , 'test@testov.test');
$I->fillField(['id' => 'psw_main_login'] , 'department11111');
$I->Click('form[name=main_login_form] button[type=submit]');
$I->Click('Мой профиль');
$I->Click('Отделы');
$I->see('Testov Test');
$I->see('Отдел налогообложения');
$I->Click('Отдел налогообложения');
$I->see('Екатерина Смирнова');
$I->see('Елена Михайлова');
$I->see('Ксения Родионова');
$I->see('Мария Иванова');
$I->makeHtmlSnapshot();   
$I->Click('Мой профиль');
$I->Click('Выйти');  
$I->see('Войти');


