<?php

namespace DanBallance\OasLumen\Tests;

use PHPUnit\Framework\TestCase;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Doctrine\AnnotationEntityBuilder;
use DanBallance\OasLumen\Doctrine\EntityWriter;

class EntityWriterTest extends TestCase
{
    public function testWrite()
    {
        $path = dirname(__FILE__)  . '/fixtures/specifications/oas3/player.yml';
        $spec = Specification::fromFile($path);
        $annotationEntityBuilder = new AnnotationEntityBuilder($spec);
        $entityWriter = new EntityWriter($annotationEntityBuilder);
        $entityWriter->setNamespace('DanBallance\OasLumen\Tests\fixtures\generatedPHP');
        $this->assertEquals(
            file_get_contents(
                dirname(__FILE__)  . '/fixtures/generatedPHP/Player.php'
            ),
            $entityWriter->toString('Player')
        );
    }
}
