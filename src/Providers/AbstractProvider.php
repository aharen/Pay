<?php

namespace aharen\Pay\Providers;

use aharen\Pay\Interfaces\ProviderInterface;

abstract class AbstractProvider implements ProviderInterface
{
    abstract protected function configRules();

    public function make(array $config)
    {}

    public function callback($request)
    {}
}
