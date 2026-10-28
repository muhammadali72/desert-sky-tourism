<?php
declare(strict_types=1);
$private=dirname(__DIR__).'/dst-private';
require $private.'/core.php';
$path=rtrim(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),'/')?:'/';
if(str_starts_with($path,'/api/')){require $private.'/api.php';exit;}
if($path==='/dashboard'||$path==='/login'){require $private.'/dashboard.php';exit;}
require $private.'/site.php';
