<?php

namespace DanBallance\OasLumen\Doctrine;

use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;

class EntityWriter
{
    protected $annotationEntityBuilder;
    protected $namespace = 'app\\Entities';

    public function __construct(AnnotationEntityBuilder $annotationEntityBuilder)
    {
        $this->annotationEntityBuilder = $annotationEntityBuilder;
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function toString(string $entityName) : string
    {
        return $this->generate($entityName);
    }

    public function toFile(string $entityName, string $path = './')
    {
        file_put_contents(
            $this->preparePath($entityName, $path),
            $this->generate($entityName)
        );
    }

    protected function preparePath(string $entityName, string $path) : string
    {
        if (substr($path, -1) !== '/') {
            $path .= '/';  // ensure path ends with a slash
        }
        return $path .= "{$entityName}.php";
    }

    protected function generate(string $entityName) : string
    {
        $annotationEntity = $this->annotationEntityBuilder->make($entityName);
        $file = new PhpFile();
        $namespace = $file->addNamespace($this->namespace);
        $namespace->addUse('Doctrine\\ORM\\Mapping as ORM');
        $class = $namespace->addClass($entityName);
        $classAnnotation = $annotationEntity->getClassAnnotation();
        foreach ($classAnnotation->getAnnotations() as $annotation) {
            $class->addComment($annotation);
        }
        $constructorMethod = $class->addMethod('__construct')
            ->setVisibility('public');
        $constructorBody = [];
        $fieldAnnotations = $annotationEntity->getFieldAnnotations();
        foreach ($fieldAnnotations as $fieldName => $fieldAnnotation) {
            $property = $class->addProperty($fieldName);
            $property->setVisibility('protected');
            foreach ($fieldAnnotation->getAnnotations() as $annotation) {
                $property->addComment($annotation);
            }
            $method = $class->addMethod('get' . ucfirst($fieldName))
                ->setVisibility('public')
                ->setBody('return $this->' . $fieldName . ';');
            if (!$fieldAnnotation->isPrimaryKey()) {
                $method = $class->addMethod('set' . ucfirst($fieldName))
                    ->setVisibility('public')
                    ->setBody('$this->' . $fieldName . ' = $' . $fieldName . ';');
                $method->addParameter($fieldName);
                $constructorMethod->addParameter($fieldName, null);
                $constructorBody[] = '$this->' . $fieldName . ' = $' . $fieldName . ';';
            }
        }
        $constructorMethod->setBody(implode("\n", $constructorBody));
        return (new PsrPrinter)->printFile($file);
    }
}
