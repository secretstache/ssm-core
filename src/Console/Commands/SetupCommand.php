<?php

namespace SSM\Console\Commands;

use Roots\Acorn\Console\Commands\Command;

class SetupCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'ssm:setup';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'SSM: Setup';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->ask('What is your name?');
        // $name = $this->argument('name');
        return $this->info($name);
    }
}
