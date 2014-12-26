<?php

namespace RestProxy;

interface ExecuterIface
{
    public function __construct(DecoderIface $decoder);

    public function doMethod($s);

    public function getStatus();

    public function getHeaders();
}