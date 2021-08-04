<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\App;
use Setrest\OAPIDocumentation\Documentation;

class GenerateDocumentation extends App\Console\Commands\Command
{
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
     * @return int
     */
    public function handle()
    {
        Documentation::generate();
        $this->info('Success generating!');
    }
}
