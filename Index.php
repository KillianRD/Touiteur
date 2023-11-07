<?php


use iutn\touiter\db\ConnectionFactory;
use iutnc\deefy\dispatch\Dispatcher;

require_once 'vendor/autoload.php';

session_start();
//ConnectionFactory::setConfig(__DIR__ . '/src/conf/config.ini');

$dispatch = new Dispatcher();
$dispatch->run();
