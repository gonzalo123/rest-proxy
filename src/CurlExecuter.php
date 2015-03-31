<?php

namespace RestProxy;

class CurlExecuter implements ExecuterIface
{
    const HTTP_SUCCESS = 2;
    const USER_AGENT = 'gonzalo123/rest-proxy';

    private $responseHeaders = [];
    private $status;
    private $decoder;

    public function __construct(DecoderIface $decoder)
    {
        $this->decoder = $decoder;
    }

    public function doMethod($s, $headers = array())
    {
        $headers = array_merge(["User-Agent: " . self::USER_AGENT], $headers);
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($s, CURLOPT_HEADER, TRUE);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, TRUE);
        $out                   = curl_exec($s);
        $this->status          = curl_getinfo($s, CURLINFO_HTTP_CODE);
        $this->responseHeaders = curl_getinfo($s, CURLINFO_HEADER_OUT);
        curl_close($s);

        list($this->responseHeaders, $content) = $this->decoder->decodeOutput($out);
        if (!$this->isSuccessful($this->status)) {
            throw new \Exception("http error: {$this->status}", $this->status);
        }

        return $content;
    }

    private function isSuccessful($code) {
        $codeFamily = (int)($code/100);
        return $codeFamily == self::HTTP_SUCCESS;
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
