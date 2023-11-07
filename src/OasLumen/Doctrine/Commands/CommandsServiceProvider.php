<?php

namespace DanBallance\OasLumen\Doctrine\Commands;

use Illuminate\Support\ServiceProvider;

class CommandsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(GenerateEntity::class);
    }
}