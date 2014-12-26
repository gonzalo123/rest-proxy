<?php
namespace RestProxy;

class CurlWrapper
{
    private $executer;

    public function __construct(ExecuterIface $executer)
    {
        $this->executer = $executer;
    }

    public static function createWrapper()
    {
        return new self(new CurlExecuter(new OutputDecoder()));
    }

    public function doGet($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, is_null($queryString) ? $url : $url . '?' . $queryString);

        return $this->executer->doMethod($s);
    }

    public function doPost($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_POST, TRUE);
        if (!is_null($queryString)) {
            curl_setopt($s, CURLOPT_POSTFIELDS, parse_str($queryString));
        }

        return$this->executer->doMethod($s);
    }

    public function doPut($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PUT');
        if (!is_null($queryString)) {
            curl_setopt($s, CURLOPT_POSTFIELDS, parse_str($queryString));
        }

        return $this->executer->doMethod($s);
    }

    public function doDelete($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, is_null($queryString) ? $url : $url . '?' . $queryString);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!is_null($queryString)) {
            curl_setopt($s, CURLOPT_POSTFIELDS, parse_str($queryString));
        }

        return $this->executer->doMethod($s);
    }

    public function getStatus()
    {
        return $this->executer->getStatus();
    }

    public function getHeaders()
    {
        return $this->executer->getHeaders();
    }
}