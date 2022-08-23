<?php

namespace Tailslide;

use Illuminate\Support\ServiceProvider;

class TailslideServiceProvider extends ServiceProvider
{
    public function register()
    {
        // do nothing
    }

    public function boot()
    {
        Builder::register();
    }
}
