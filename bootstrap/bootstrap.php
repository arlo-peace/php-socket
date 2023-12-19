<?php
use SocketAPP\Provider\DatabaseProvider;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    DatabaseProvider::init();
} catch (Exception $e) {
    die("Database Issue: {$e}");
}