<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateFactoriesCommand extends Command
{
    protected $signature = 'redux:make-factory
    {module     : The name of the module}
    {factory    : The name of the factory}';

    protected $description = 'Create new module factory';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $factory = ucfirst($this->argument('factory'));

        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;
        $factoryName = $this->AddSuffix($factory);

        $GeneratePath = $moduleFolder . "/Database/Factories/{$factoryName}.php";

        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->components->error("Factory [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $factoryName, 'factories');
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created Factory [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }

    private function AddSuffix($factoryName) {
        $suffix = 'Factory';
        $suffixLength = strlen($suffix);
        $hasSuffix = ($suffixLength === 0 || (substr($factoryName, -$suffixLength) === $suffix));

        return $factoryName.(!$hasSuffix ? $suffix : '');
    }

}
