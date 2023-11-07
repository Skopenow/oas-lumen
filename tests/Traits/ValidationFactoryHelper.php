<?php

namespace DanBallance\OasLumen\Tests\Traits;

use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Validation\Factory;

trait ValidationFactoryHelper
{
    protected function makeValidationFactory()
    {
        $testTranslationPath = __DIR__ . '/lang';
        $fileLoader = new FileLoader(
            new Filesystem,
            $testTranslationPath)
        ;
        $translator = new Translator($fileLoader, 'en');
        return new Factory($translator);
    }
}
