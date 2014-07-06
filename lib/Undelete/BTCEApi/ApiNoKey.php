<?php

namespace Undelete\BTCEApi;

class ApiNoKey
{
    /**
     * @param $pair
     * @param $method
     * @return []
     */
    protected function get($pair, $method)
    {
        $url = sprintf("https://btc-e.com/api/2/%s/%s", $pair, $method);

        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0
        ]);

        $response = curl_exec($curl);

        if (!$response) {
            $this->throwCurlException($curl);
        }

        return json_decode($response, true);
    }

    protected function throwCurlException($curl)
    {
        throw new ApiException(sprintf('Curl error #%d: %s', curl_errno($curl), curl_error($curl)));
    }

    public function getFee($pair)
    {
        return $this->get($pair, 'fee')['trade'];
    }

    public function getTicker($pair)
    {
        return $this->get($pair, 'ticker')['ticker'];
    }

    public function getTrades($pair)
    {
        return $this->get($pair, 'trades');
    }

    public function getDepth($pair)
    {
        return $this->get($pair, 'depth');
    }
}
