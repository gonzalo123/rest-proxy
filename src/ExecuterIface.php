<?php

namespace RestProxy;

interface ExecuterIface
{
    public function __construct(DecoderIface $decoder);

    public function doMethod($s, $headers = array());

    public function getStatus();

    public function getHeaders();
}
