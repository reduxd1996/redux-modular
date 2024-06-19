<?php

namespace Redux\Modular\Utils;


class FileAndFolderUtils extends Utils {


    public function __invoke(){
        return self::$module_path;
    }
    public static function GenerateFolders(){
        return self::$module_folers;
    }


    public function CreateFolderAndFile(string $module,string $folder, string $file_name, string $type){
        $response = '';

        switch ($type){
            case 'controller':
                break;
            case 'model':
                $response = $this->checkModel($module, $file_name);
                break;
            default:
                dd('you have no option');
                break;
        }


        return $response;
    }

    private function checkModel($module, $model){
        $path = self::$module_path['App']['Models'];
        return self::$module_path;
    }

    private function checkRequest($module, $request){
        $path = self::$module_path['App']['Http']['Request'];
    }

    private function checkService($module, $request){
        $path = self::$module_path['App']['Services'];
    }

    private function checkController($module, $controller){

    }

    private function checkResource($module, $controller){

    }
}
