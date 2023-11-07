<?php

namespace DanBallance\OasLumen\Commands;

use Illuminate\Support\ServiceProvider;
use Appzcoder\LumenRoutesList\RoutesCommand;

class CommandsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands(RoutesCommand::class);
    }
}