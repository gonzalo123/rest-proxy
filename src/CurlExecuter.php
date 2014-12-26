<?php

namespace RestProxy;

class CurlExecuter implements ExecuterIface
{
    const HTTP_OK = 200;
    const USER_AGENT = 'gonzalo123/rest-proxy';

    private $responseHeaders = [];
    private $status;
    private $decoder;

    public function __construct(DecoderIface $decoder)
    {
        $this->decoder = $decoder;
    }

    public function doMethod($s)
    {
        $headers = ["User-Agent: " . self::USER_AGENT];
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($s, CURLOPT_HEADER, TRUE);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, TRUE);
        $out                   = curl_exec($s);
        $this->status          = curl_getinfo($s, CURLINFO_HTTP_CODE);
        $this->responseHeaders = curl_getinfo($s, CURLINFO_HEADER_OUT);
        curl_close($s);

        list($this->responseHeaders, $content) = $this->decoder->decodeOutput($out);
        if ($this->status != self::HTTP_OK) {
            throw new \Exception("http error: {$this->status}", $this->status);
        }

        return $content;
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