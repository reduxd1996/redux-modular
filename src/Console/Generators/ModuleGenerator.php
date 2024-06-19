<?php
namespace Redux\Modular\Console\Generators;

class ModuleGenerator extends Generator {

    protected $module_folders = [];

    public function __construct(){
        $this->module_folders = [
            'App' => [
                'Http' => [
                    'Controllers',
                    'Requests',
                    'Resources',
                    'Middlewares'
                ],
                'Models',
                'Exports',
                'Imports',
                'Rules',
                'Providers',
                'Traits',
                'Services',
                'Interfaces',
                'Policies'
            ],
            'Database' => [
                'Factories',
                'Migrations',
                'Seeders'
            ],
            'routes',
            'tests',
            'views'
        ];
    }

    public function createModuleFolder($module_path, $module){
        $parentModulePath = $module_path;

        $namespace_module = ucfirst($parentModulePath);
        $replace = [
            '{{namespace}}' => "{$namespace_module}\\$module\\",
            '{{class}}' => $module,
        ];

        $modulesFolder = rtrim($parentModulePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // Create the full path to the subdirectory
        $moduleDirectory = $modulesFolder . $module;

        if (!is_dir($moduleDirectory)) {
            // Create the subdirectory
            if (!mkdir($moduleDirectory, 0755, true)) {
                throw new \Exception("Failed to create directory: $moduleDirectory");
            }
        }

        foreach($this->module_folders as $folder => $sub_folders){
            $modulesSubNonAppFolder = rtrim($parentModulePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $ModuleFolders = $modulesSubNonAppFolder . $module ;
            if(is_array($sub_folders)){
                $AppDBFolder = $ModuleFolders . '/' . $folder;
                if (!is_dir($AppDBFolder)) {
                    if (!mkdir($AppDBFolder, 0755, true)) {
                        throw new \Exception("Failed to create directory: $AppDBFolder");
                        exit;
                    }
                }
                foreach($sub_folders as $key => $ABFolders){
                    if (is_array($ABFolders)) {
                        $SubAppHtppFolders = $AppDBFolder . '/' . $key;
                        if (!is_dir($SubAppHtppFolders)) {
                            if (!mkdir($SubAppHtppFolders, 0755, true)) {
                                throw new \Exception("Failed to create directory: $SubAppHtppFolders");
                                exit;
                            }
                        }
                        foreach ($ABFolders as $http_key => $HttpFolders) {
                            $SubAHtppFolders = $SubAppHtppFolders . '/' . $HttpFolders;
                            if (!is_dir($SubAHtppFolders)) {
                                if (!mkdir($SubAHtppFolders, 0755, true)) {
                                    throw new \Exception("Failed to create directory: $SubAHtppFolders");
                                    exit;
                                }
                            }

                            if ($HttpFolders == 'Controllers') {
                                $replace['{{name}}'] = "{$module}Controller";
                                $this->createStub($SubAHtppFolders . "/{$module}Controller.php", "Controllers", $replace);
                            }

                            if ($HttpFolders == 'Requests') {
                                $this->createStub($SubAHtppFolders . "/{$module}Request.php", "Requests", $replace);
                            }

                            if ($HttpFolders == 'Resources') {
                                $this->createStub($SubAHtppFolders . "/{$module}Resource.php", "Resources", $replace);
                            }
                        }
                    } else {
                        $SubAppDBFolders = $AppDBFolder.'/'. $ABFolders;
                        if (!is_dir($SubAppDBFolders)) {
                            if (!mkdir($SubAppDBFolders, 0755, true)) {
                                throw new \Exception("Failed to create directory: $SubAppDBFolders");
                                exit;
                            }
                        }

                        if ($ABFolders == 'Models') {
                            $this->createStub($SubAppDBFolders . "/{$module}.php", "Models", $replace);
                        }

                        if ($ABFolders == 'Providers') {
                            $replace['{{module}}'] = $parentModulePath;
                            $this->createStub($SubAppDBFolders . "/{$module}ServiceProvider.php", "moduleserviceprovider", $replace);
                            $this->createStub($SubAppDBFolders . "/RouteServiceProvider.php", "routeserviceprovider", $replace);
                        }

                        if ($ABFolders == 'Services') {
                            $this->createStub($SubAppDBFolders . "/{$module}Service.php", "services", $replace);
                        }

                        if ($ABFolders == 'Policies') {
                            $this->createStub($SubAppDBFolders . "/{$module}Policy.php", "policies", $replace);
                        }
                    }
                }
            } else {
                $NonAppFolders = $ModuleFolders . '/' . $sub_folders;
                if (!is_dir($NonAppFolders)) {
                    if (!mkdir($NonAppFolders, 0755, true)) {
                        throw new \Exception("Failed to create directory: $NonAppFolders");
                        exit;
                    }
                }
                if($sub_folders == 'tests'){
                    if (!is_dir($NonAppFolders . '/Feature')) {
                        if (!mkdir($NonAppFolders . '/Feature', 0755, true)) {
                            throw new \Exception("Failed to create directory: Tests/Feature");
                            exit;
                        }
                    }
                    if (!is_dir($NonAppFolders . '/Unit')) {
                        if (!mkdir($NonAppFolders . '/Unit', 0755, true)) {
                            throw new \Exception("Failed to create directory: Tests/Unit");
                            exit;
                        }
                    }
                }

                if($sub_folders == 'routes'){
                    $this->createStub($NonAppFolders.'/api.php','apiroutes', $replace);
                    $this->createStub($NonAppFolders.'/web.php','webroutes', $replace);
                }
            }
        }

        return true;
    }
}
?>
