<?php

namespace DanBallance\OasLumen\Serializers;

use League\Fractal\Serializer\SerializerAbstract as LeagueSerializerAbstract;



abstract class SerializerAbstract extends LeagueSerializerAbstract
{
    abstract public function getContentType(): string;
}
