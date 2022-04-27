<?php

namespace JuheMark\Kernel;

trait Response
{
    public function fail($value, $status = 0)
    {
        exit(json_encode([
            'message' => $value,
            'status'  => $status,
        ]));
    }
}
