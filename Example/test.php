<?php

require_once __DIR__ . '/../loader.php';

$pair = 'btc_usd';
$api = new Undelete\BTCEApi\ApiNoKey();

try {
    $fee = $api->getFee($pair);
    $depth = $api->getDepth($pair);
    $ticker = $api->getTicker($pair);
    $trades = $api->getTrades($pair);
} catch (\Undelete\BTCEApi\ApiException $e) {
    printf("Erorr! %s", $e->getMessage());
    die;
}

$firstAsk = $depth['asks'][0];
$firstBid = $depth['bids'][0];

printf("Pair %s fee: %.2f\n", $pair, $fee);
printf("First ask %.4f (%.4f) First bid %.4f (%.4f)\n", $firstAsk[0], $firstAsk[1], $firstBid[0], $firstBid[1]);
printf("High: %.4f Low: %.4f Last: %.4f\n", $ticker['high'], $ticker['low'], $ticker['last']);
printf("Last trade %s %.4f (%.4f)", $trades[0]['trade_type'], $trades[0]['price'], $trades[0]['amount']);
