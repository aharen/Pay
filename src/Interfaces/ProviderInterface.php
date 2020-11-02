<?php

namespace aharen\Pay\Interfaces;

interface ProviderInterface
{
    public function make(array $config);

    public function callback(array $response, string $orderId);
}
