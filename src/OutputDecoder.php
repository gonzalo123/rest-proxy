<?php

namespace RestProxy;

class OutputDecoder implements DecoderIface
{
    public function decodeOutput($out)
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
}