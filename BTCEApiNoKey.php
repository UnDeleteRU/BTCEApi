<?php

class BTCEApiNoKey
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

        $result = curl_exec($curl);

        if (!$result) {
            throw new BTCEApiException(sprintf('Curl error #%d: %s', curl_errno($curl), curl_error($curl)));
        }

        return json_decode($result, true);
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
