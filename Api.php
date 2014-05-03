<?php

namespace Undelete\BTCEApi;

class Api extends ApiNoKey
{
    private $apiKey;

    private $apiSecret;

    private $noOnce;

    private $funds;

    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->noOnce = time();
    }

    public function getFunds()
    {
        if (!$this->funds) {
            $result = $this->apiQuery('getInfo');

            if (!$result) {
                throw new ApiException('Info not avalible');
            }

            $this->funds = $result['funds'];
        }

        return $this->funds;
    }

    public function orderTrade($pair, $type, $rate, $amount)
    {
        $data = array(
            'pair' => $pair,
            'type' => $type,
            'rate' => $rate,
            'amount' => $amount,
        );

        $response = $this->apiQuery('Trade', $data);

        if ($response) {
            return $response['order_id'];
        }

        return null;
    }

    public function cancelOrder($orderId)
    {
        $response = $this->apiQuery('CancelOrder', array(
            'order_id' => $orderId,
        ));

        return $response;
    }

    public function getActiveOrders()
    {
        return $this->apiQuery('ActiveOrders');
    }

    protected function getNoOnce()
    {
        return $this->noOnce++;
    }

    public function apiQuery($method, $req = array())
    {
        $req['method'] = $method;
        $req['nonce'] = $this->getNoOnce();

        // generate the POST data string
        $post_data = http_build_query($req, '', '&');

        // Generate the keyed hash value to post
        $sign = hash_hmac("sha512", $post_data, $this->apiSecret);

        // Add to the headers
        $headers = array(
            'Sign: '.$sign,
            'Key: '.$this->apiKey,
        );

        $curl = curl_init('https://btc-e.com/tapi/');

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $post_data,
        ));

        $response = curl_exec($curl);

        if (!$response) {
            $this->throwCurlException($curl);
        }

        $result = json_decode($response, true);

        if ($result['success']) {
            if (isset($result['funds'])) {
                $this->funds = $result['funds'];
            }

            return $result['return'];
        }

        return null;
    }
}
