<?php
include 'ConfigParser.php';

$configParser = new ConfigParser('config.txt');

echo "<pre>";
var_dump($configParser->parseConfig());