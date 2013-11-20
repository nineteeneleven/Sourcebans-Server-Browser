<?php
//PHP 5+ only

if(!defined('NineteenEleven')){die('Direct access not premitted');}
//Fill in your preferences, and information

define("SB_HOST" , "localhost");      //set MySQL host ONLY NEEDED IF SOURCEBANS IS ON A DIFFERENT MYSQL SERVER
define("SB_USER" , "root");         //MySQL username ONLY NEEDED IF SOURCEBANS IS ON A DIFFERENT MYSQL SERVER
define("SB_PASS" , "password2strong");       //MySQL password ONLY NEEDED IF SOURCEBANS IS ON A DIFFERENT MYSQL SERVER
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
define('SOURCEBANS_DB', 'sourcebans'); // sourcebans database, this is needed.
define('SB_PREFIX', 'sb'); //Sourcebans database prefix. Only change this value if you changed your database prefix when setting up SourceBans.
?>