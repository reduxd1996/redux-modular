<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\FileGenerator;
use Redux\Modular\Utils\FileAndFolderUtils;
use Redux\Modular\Utils\FileTypeEnum;

class GenerateCommand extends Command
{
    protected $signature = 'redux:generate
    {module      : The name of the module}
    {generate    : The type of file to generate}
    {file        : The file to generate}';

    protected $description = 'Generate new file';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));

        $typeOfFileToGenerate = $this->argument('generate');

        $file = $this->argument('file');

        if (empty($module)) {
            $this->error("module is required");
            return;
        }

        $utils = new FileAndFolderUtils;
        $generator = new FileGenerator;
        dd($utils::GenerateFolders());
        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $moduleFolder = $modulesPathFolder . $module;


        // $GeneratePath = $moduleFolder . "/App/Traits/{$trait}.php";

        // if (is_dir($moduleFolder)) {
        //     if (file_exists($GeneratePath)) {
        //         $this->error("Trait {$trait} in {$module} already exists");
        //     } else {
        //         $generator->createFile($module, $GeneratePath, $trait, 'traits');
        //         if (file_exists($GeneratePath)) {
        //             $this->info("Created Trait {$trait} in {$module} module");
        //         }
        //     }
        // } else {
        //     $this->error("Module {$module} doesn't exists");
        // }
    }
}
