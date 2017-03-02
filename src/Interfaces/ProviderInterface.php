<?php

namespace aharen\Pay\interfaces;

interface ProviderInterface
{
    public function make(array $config);

    public function callback($request);
}
