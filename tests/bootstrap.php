<?php
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  limingxin@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';
require_once dirname(dirname(__FILE__)) . '/tests/config/define.php';

// init
\Swoft\Bean\BeanFactory::init();

/* @var \Swoft\Bootstrap\Boots\Bootable $bootstrap*/
$bootstrap = \Swoft\App::getBean(\Swoft\Bootstrap\Bootstrap::class);
$bootstrap->bootstrap();

$console = new \Swoft\Console\Console();
$console->run();

//\Swoft\Bean\BeanFactory::reload();
//
//$initApplicationContext = new \Swoft\Core\InitApplicationContext();
//$initApplicationContext->init();
//\Swoft\App::$isInTest = true;