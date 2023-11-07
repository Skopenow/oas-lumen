<?php

namespace DanBallance\OasLumen\Doctrine\Commands;

use Illuminate\Console\Command;
use DanBallance\OasLumen\Specification;
use DanBallance\OasLumen\Doctrine\AnnotationEntityBuilder;
use DanBallance\OasLumen\Doctrine\EntityWriter;

class GenerateEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oas:generate:entity {--entity=all} {--spec=./api.yml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Doctrine entities from OAS schemas.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entityName = $this->option('entity');
        $pathToSpec = $this->option('spec');
        $spec = Specification::fromFile($pathToSpec);
        $annotationEntityBuilder = new AnnotationEntityBuilder($spec);
        $entityWriter = new EntityWriter($annotationEntityBuilder);
        $entityWriter->toFile($entityName);
    }
}
