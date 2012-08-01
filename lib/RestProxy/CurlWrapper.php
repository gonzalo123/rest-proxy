<?php
namespace RestProxy;

class CurlWrapper
{
    const HTTP_OK = 200;

    public function doGet($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, is_null($queryString) ? $url : $url . '?' . $queryString);

        return $this->doMethod($s);
    }

    public function doPost($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_POST, TRUE);
        if (!is_null($queryString)) {
            curl_setopt($s, CURLOPT_POSTFIELDS, parse_str($queryString));
        }
        return $this->doMethod($s);
    }

    public function doPut($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PUT');
        if (!is_null($queryString)) {
            curl_setopt($s, CURLOPT_POSTFIELDS, parse_str($queryString));
        }
        return $this->doMethod($s);
    }

    public function doDelete($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, is_null($queryString) ? $url : $url . '?' . $queryString);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!is_null($queryString)) {
            curl_setopt($s, CURLOPT_POSTFIELDS, parse_str($queryString));
        }
        return $this->doMethod($s);
    }

    private function doMethod($s)
    {
        curl_setopt($s, CURLOPT_HEADER, 0);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, TRUE);
        $out    = curl_exec($s);
        $status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        curl_close($s);

        if ($status != self::HTTP_OK) {
            throw new \Exception("http error: {$status}", $status);
        }

        $headers = curl_getinfo($s);
        print_r($headers);
        die();
        return $out;
    }
}
