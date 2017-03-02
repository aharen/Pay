<?php

namespace aharen\Pay;

class Gateway
{
    protected $provider;

    public function __construct()
    {
    }

    protected function init(string $provider)
    {
        $this->provider = $this->makeProvider($provider);
    }

    protected function makeProvider(string $provider)
    {
        $providerClass = "\aharen\Pay\Providers\$provider";
        return get_class(new $providerClass());
    }

    public function make($config)
    {
        $this->init();
    }
}
