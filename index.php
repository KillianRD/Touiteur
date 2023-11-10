<?php


use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();
ConnectionFactory::setConfig(__DIR__ . '/src/conf/db.config.ini');

$dispatch = new Dispatcher();
$dispatch->run();