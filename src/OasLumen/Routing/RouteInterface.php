<?php

namespace DanBallance\OasLumen\Routing;

interface RouteInterface
{
    public function getMethod() : string;
    public function getUri() : string;
    public function getResource() : string;
    public function getName() : string;
    public function getNamespace() : string;
    public function getController() : string;
    public function getAction() : string;
    public function getOperationId() : ?string;
}
