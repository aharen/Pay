<?php

namespace aharen\Pay;

class Gateway
{
    protected $provider;

    public static function create(string $provide)
    {
        return $provider;
        // return $this;
    }

    // protected $provider;

    // public function __construct()
    // {
    // }

    // protected function construct(string $provider)
    // {
    //     $this->provider = $this->makeProvider($provider);
    // }

    // protected function makeProvider(string $provider)
    // {
    //     $providerClass = "\aharen\Pay\Providers\$provider";
    //     return get_class(new $providerClass());
    // }

    // public function make($config)
    // {
    //     $this->construct();
    // }
}
