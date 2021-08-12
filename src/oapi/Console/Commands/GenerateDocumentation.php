<?php

namespace Setrest\OAPIDocumentation\Console\Commands;

use Setrest\OAPIDocumentation\DocumentationFactory;

class GenerateDocumentation extends Command
{
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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oapi:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Document generation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(DocumentationFactory $documentation)
    {
        $documentation = $documentation->make();
        $documentation->generate();
        $this->info('Success generating!');
    }
}
