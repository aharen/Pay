<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    die('No vendor autoload (did you composer install?)');
}
$loader = include $autoload;
$loader->add('aharen', __DIR__ . '/../src/');
