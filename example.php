<?php

require __DIR__ . '/src/edsonmedina/bittrex/Client.php';

use edsonmedina\bittrex\Client;

$key = 'ffffff';
$secret = '000000';

try {
    $b = new Client($key, $secret);
    var_dump($b->getOrderHistory());
    echo PHP_EOL . PHP_EOL;
} catch (Exception $e) {
    echo sprintf('ERROR: %s' . PHP_EOL . PHP_EOL, $e->getMessage());
}