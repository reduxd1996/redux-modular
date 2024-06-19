<?php

namespace Redux\Modular;

use Illuminate\Support\ServiceProvider;

class ReduxModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/redux-modular.php', 'redux-modular');
        $exclude = ['GenerateCommand.php'];
        $commands = [];
        foreach($this->getCommandsFolders() as $key => $value){
            if(in_array($value, $exclude)){ continue; }
            $command = explode('.',$value)[0];
            $commandClass = "Redux\\Modular\\Console\\Commands\\" . $command;
            if (class_exists($commandClass)) {
                $commands[] = $commandClass;
            }
        }

        // Register your package's commands
        if ($this->app->runningInConsole()) {
            $this->commands($commands);
        }
    }

    public function boot()
    {

        $this->publishes([
            __DIR__ . '/config/redux-modular.php' => config_path('redux-modular'),
        ], 'config');

        // Boot your package's services

        $modules_folders = $this->getModulesFolder();

        $module_prefix = config('redux-modular.modulePath');
        $allClasses = get_declared_classes();

        foreach ($modules_folders as $module) {
            //Module Service Provider must have a prefix of the module name. example User Module the service provider must be UserServiceProvider
            $moduleServiceProviderFile = base_path($module_prefix."/{$module}/App/Providers/{$module}ServiceProvider.php");

            $moduleServiceProvider = $module_prefix."\\{$module}\\App\\Providers\\{$module}ServiceProvider";
            if (file_exists($moduleServiceProviderFile)) {

                if (class_exists($moduleServiceProvider)) {
                    $this->app->register($moduleServiceProvider);
                } else {
                    throw new \Exception("The {$moduleServiceProvider} not found.");
                }
            } else {
                throw new \Exception("$moduleServiceProviderFile file not found.");
            }
        }
    }

    private function getModulesFolder()
    {
        $module = config('redux-modular.modulePath');
        $directory = base_path($module);

        $modulesFolders = [];
        // Check if the directory exists

        if (is_dir($directory)) {
            // Scan the directory and get all items
            $items = scandir($directory);

            // Loop through the items
            foreach ($items as $item) {
                // Skip the current and parent directory references
                if ($item !== '.' && $item !== '..') {
                    // Full path of the item
                    $itemPath = $directory . DIRECTORY_SEPARATOR . $item;

                    // Check if the item is a directory and exclude some folder ex. Template
                    if (is_dir($itemPath)) {
                        $modulesFolders[] = $item;
                    }
                }
            }
        }

        return $modulesFolders;
    }

    private function getCommandsFolders()
    {

        $directory = __DIR__.'/Console/Commands';

        $commandFolders = [];

        // Check if the directory exists
        if (is_dir($directory)) {
            // Scan the directory and get all items
            $items = scandir($directory);

            // Loop through the items
            foreach ($items as $item) {
                // Skip the current and parent directory references
                if ($item !== '.' && $item !== '..') {
                    // Full path of the item
                    $itemPath = $directory . DIRECTORY_SEPARATOR . $item;

                    // Check if the item is a directory and exclude some folder ex. Template
                    if (file_exists($itemPath)) {
                        $commandFolders[] = $item;
                    }
                }
            }
        }

        return $commandFolders;
    }
}
