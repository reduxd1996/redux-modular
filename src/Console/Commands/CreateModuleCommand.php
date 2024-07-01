<?php

namespace Redux\Modular\Console\Commands;

use Illuminate\Console\Command;
use Redux\Modular\Console\Generators\ModuleGenerator;
use Redux\Modular\Console\Generators\FileGenerator;

class CreateModuleCommand extends Command
{
    protected $signature = 'redux:make-module
                            {module     : The name of the module}
                            {--with=    : with repository, trait, interface,  or service or combined }
    ';
    protected $description = 'Create new module';

    public function handle()
    {
        $module_path = config('redux-modular.modulePath');

        $module = ucfirst($this->argument('module'));
        $with_f = $this->option('with');

        $with_flag = [
            'repository' => [
                'path' => '/App/Repositories/',
                'name' => "{$module}Repository",
                'type' => 'repositories',
            ],
            'trait' => [
                'path' => '/App/Traits/',
                'name' => "{$module}Trait",
                'type' => 'traits',
            ],
            'service' => [
                'path' => '/App/Services/',
                'name' => "{$module}Service",
                'type' => 'normalservice',
            ],
            'interface' => [
                'path' => '/App/Interfaces/',
                'name' => "{$module}Interface",
                'type' => 'interfaces'
            ],
        ];

        if (empty($module)) {
            $this->components->error("module is required");
            return;
        }

        if($this->isMultipleWith($with_f)){
            $flags = explode(',', $with_f);
            $errors = [];
            foreach ($flags as $flag) {
                if(empty($flag)){
                    continue;
                }
                if (!array_key_exists(strtolower($flag), $with_flag)) {
                    $errors[] = $flag;
                    $this->components->error("{$flag} is not valid");
                }
            }

            if(!empty($errors)){
                return ;
            }
        } else {
            if(!array_key_exists(strtolower($with_f),$with_flag)){
                $this->components->error("{$with_f} is not valid");
                return;
            }
        }
        $generator      = new ModuleGenerator;
        $with_generator = new FileGenerator;

        $modulesPathFolder = rtrim($module_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $moduleFolder = $modulesPathFolder . $module;

        // Check if the module folder exists
        if (!is_dir($modulesPathFolder)) {
            // Create the module folder
            if (!mkdir($modulesPathFolder, 0755, true)) {
                throw new \Exception("Failed to create directory: $modulesPathFolder");
                exit;
            }
        }

        if (is_dir($module_path)) {
            if (is_dir($module_path)) {
                if (!is_dir($moduleFolder)) {
                    $generate_module_folders = $generator->createModuleFolder($module_path, $module);

                    if ($generate_module_folders) {
                        $this->components->info("Generated [{$module}] scaffolding successfully.");
                        if ($this->isMultipleWith($with_f)) {
                            $flags = explode(',', $with_f);
                            foreach ($flags as $key => $flag) {
                                if (empty($flag)) {
                                    continue;
                                }
                                $_flag = strtolower($flag);
                                $folder_type = ucfirst($with_flag[$_flag]['type']);
                                $type = ucfirst($_flag);
                                if (array_key_exists(strtolower($_flag), $with_flag)) {
                                    $flagPath = $moduleFolder . $with_flag[$_flag]['path'];
                                    if (!is_dir($flagPath)) {
                                        if (!mkdir($flagPath, 0755, true)) {
                                            throw new \Exception("Failed to create directory: {$folder_type} Folder");
                                            exit;
                                        }
                                    }
                                    $GenerateWithPath = "{$flagPath}{$with_flag[$_flag]['name']}.php";

                                    if (file_exists($GenerateWithPath)) {
                                        $this->components->error("{$type} [{$GenerateWithPath}] already exists.");
                                    } else {
                                        $with_generator->createFile($module, $GenerateWithPath, $with_flag[$_flag]['name'], $with_flag[$_flag]['type']);
                                        if (file_exists($GenerateWithPath)) {

                                            $this->components->info("Created {$type} [{$GenerateWithPath}] successfully.");
                                        }
                                    }
                                }
                            }
                        } else {
                            if (array_key_exists(strtolower($with_f), $with_flag)) {
                                $flagPath = $moduleFolder . $with_flag[$with_f]['path'];
                                $folder_type = ucfirst($with_flag[$with_f]['type']);
                                $type = ucfirst($with_f);
                                if (!is_dir($flagPath)) {
                                    if (!mkdir($flagPath, 0755, true)) {
                                        throw new \Exception("Failed to create directory: {$folder_type} Folder");
                                        exit;
                                    }
                                }
                                $GenerateWithPath = "{$flagPath}{$with_flag[$with_f]['name']}.php";

                                if (file_exists($GenerateWithPath)) {
                                    $this->components->error("{$type} [{$GenerateWithPath}] already exists.");
                                } else {
                                    $with_generator->createFile($module, $GenerateWithPath, $with_flag[$with_f]['name'], $with_flag[$with_f]['type']);
                                    if (file_exists($GenerateWithPath)) {

                                        $this->components->info("Created {$type} [{$GenerateWithPath}] successfully.");
                                    }
                                }
                            }
                        }
                    } else {
                        $this->components->error("Error on creating [{$module}] scaffolding.");
                    }
                } else {
                    $this->components->error("Module [{$module}] already exists.");
                }
            }
        }
    }

    private function isMultipleWith($string, $char = ',')
    {
        return preg_match('/' . preg_quote($char, '/') . '/', $string) === 1;
    }
}
