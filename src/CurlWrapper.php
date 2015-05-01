<?php
namespace RestProxy;

class CurlWrapper
{
    const HTTP_OK = 200;
    const USER_AGENT = 'gonzalo123/rest-proxy';

    private $responseHeaders = [];
    private $requestHeaders = [];
    private $options = [];
    private $status;

    function __construct($requestHeaders = [], $options = [])
    {
        if (count($requestHeaders) > 0 && is_array($requestHeaders)) {
            $this->requestHeaders   = $requestHeaders;
            $this->requestHeaders[] = "User-Agent: " . self::USER_AGENT;
        } else {
            $this->requestHeaders = ["User-Agent: " . self::USER_AGENT];
        }
        if (count($options) > 0 && is_array($options)) {
            $this->options = $options;
        }
    }


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
        curl_setopt($s, CURLOPT_HTTPHEADER, $this->requestHeaders);
        curl_setopt($s, CURLOPT_HEADER, TRUE);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, TRUE);
        foreach ($this->options as $option => $value) {
            curl_setopt($s, $option, $value);
        }
        $out                   = curl_exec($s);
        $this->status          = curl_getinfo($s, CURLINFO_HTTP_CODE);
        $this->responseHeaders = curl_getinfo($s, CURLINFO_HEADER_OUT);
        curl_close($s);

        list($this->responseHeaders, $content) = $this->decodeOut($out);
        if ($this->status != self::HTTP_OK) {
            throw new \Exception("http error: {$this->status}", $this->status);
        }

        return $content;
    }

    private function decodeOut($out)
    {
        // It should be a fancy way to do that :(
        $headersFinished = FALSE;
        $headers         = $content = [];
        $data            = explode("\n", $out);
        foreach ($data as $line) {
            if (trim($line) == '') {
                $headersFinished = TRUE;
            } else {
                if ($headersFinished === FALSE && strpos($line, ':') > 0) {
                    $headers[] = $line;
                }

                if ($headersFinished) {
                    $content[] = $line;
                }
            }
        }

        return [$headers, implode("\n", $content)];
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getHeaders()
    {
        return $this->responseHeaders;
    }
}
