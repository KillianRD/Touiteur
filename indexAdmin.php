<?php


use iutnc\touiteur\admin\db\ConnectionFactory;
use iutnc\touiteur\admin\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();
ConnectionFactory::setConfig(__DIR__ . '/srcAdmin/conf/db.config.ini');

$dispatch = new Dispatcher();
$dispatch->run();