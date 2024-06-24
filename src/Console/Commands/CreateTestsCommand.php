<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateTestsCommand extends Command
{
    protected $signature = 'redux:make-test
    {module         : The name of the module}
    {test           : The name of the test}
    {--unit         : Is Test Unit}';
    protected $description = 'Create new module feature/unit test';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $test = ucfirst($this->argument('test'));
        $isUnit = $this->option('unit');

        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        $generator = new FileGenerator;
        $sub_test_folder = $isUnit ? 'Unit' : 'Feature';
        $stub = $isUnit ? 'unittest' : 'featuretest';
        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $moduleFolder = $modulesPathFolder . $module;
        $testName = $this->AddTestSuffix($test);
        $test_type = $isUnit ? 'Unit' : 'Feature';
        $GeneratePath = $moduleFolder . "/tests/{$sub_test_folder}/{$testName}.php";
        if (is_dir($moduleFolder)) {
            if (file_exists($GeneratePath)) {
                $this->components->error("{$test_type} Test [{$GeneratePath}] already exists.");
            } else {
                $generator->createFile($module, $GeneratePath, $testName, $stub);
                if (file_exists($GeneratePath)) {
                    $this->components->info("Created {$test_type} Test [{$GeneratePath}] successfully.");
                }
            }
        } else {
            $this->components->error("Module [{$module}] doesn't exists.");
        }
    }


    private function AddTestSuffix($factoryName)
    {
        $suffix = 'Test';
        $suffixLength = strlen($suffix);
        $hasSuffix = ($suffixLength === 0 || (substr($factoryName, -$suffixLength) === $suffix));

        return $factoryName . (!$hasSuffix ? $suffix : '');
    }
}
