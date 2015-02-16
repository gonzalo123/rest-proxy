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

    private function initCurl($url, $queryString = NULL)
    {
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, is_null($queryString) ? $url : $url . '?' . $queryString);

        return $s;
    }

    public function doGet($url, $queryString = NULL)
    {
        $s = $this->initCurl($url, $queryString);
        return $this->executer->doMethod($s);
    }

    private function addContent($s, $content, $contentType)
    {
        $headers = array();

        if (!empty($content))
        {
            curl_setopt($s, CURLOPT_POSTFIELDS, $content);
            $jsonType = 'application/json';
            if (strncmp($contentType, $jsonType, strlen($jsonType)) == 0) {
                $headers[] = 'Content-Type: application/json';
            }
        }

        return $headers;
    }

    public function doPost($url, $queryString = NULL, $content = NULL, $contentType = NULL)
    {
        $s = $this->initCurl($url, $queryString);
        curl_setopt($s, CURLOPT_POST, TRUE);
        $headers = $this->addContent($s, $content, $contentType);

        return$this->executer->doMethod($s, $headers);
    }

    public function doPut($url, $queryString = NULL, $content = NULL, $contentType = NULL)
    {
        $s = $this->initCurl($url, $queryString);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PUT');
        $headers = $this->addContent($s, $content, $contentType);

        return $this->executer->doMethod($s, $headers);
    }

    public function doDelete($url, $queryString = NULL, $content = NULL, $contentType = NULL)
    {
        $s = $this->initCurl($url, $queryString);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $headers = $this->addContent($s, $content, $contentType);

        return $this->executer->doMethod($s, $headers);
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
