<?php

namespace SSM\Console\Commands;

use Roots\Acorn\Console\Commands\Command;
use Illuminate\Filesystem\Filesystem;

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
     * The plugin destination.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new Eject Blocks command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		//Scaffolding files
		$this->files->copyDirectory("theme-boilerplate/src/app", "app/");
		$this->files->copyDirectory("theme-boilerplate/src/config", "config/");
		$this->files->copyDirectory("theme-boilerplate/src/resources", "resources/");

		$this->files->deleteDirectory("theme-boilerplate/");

		return $this->info("Success.");
    }
}
