<?php

namespace Redux\Modular\Console\Generators;

use Illuminate\Support\Facades\File;

class MigrationsGenerator extends Generator
{
    public function createMigrationFile($path, $name)
    {
        $replace = [
            '{{table_name}}' => $name,
        ];

        $this->createStub($path, 'Migrations', $replace);
    }
}
