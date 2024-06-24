<?php
namespace Redux\Modular\Traits;

trait Migration {

    public function migrationTableName($name, $table_name = '')
    {
        $migration = [];
        $migration_name = strtolower($name);
        $pos_create = strpos($migration_name, 'create_');
        $pos_table = strpos($migration_name, '_table');
        if (strpos($migration_name, 'create_') !== false && strpos($migration_name, '_table') !== false) {
            $migration['stub'] = 'migrations';
            if ($pos_create !== false && $pos_table !== false) {
                $migration['table_name'] = substr($migration_name, $pos_create + strlen('create_'), $pos_table - ($pos_create + strlen('create_')));
                // Extract the substring between "_create_" and "_table"
            } else {
                $migration['table_name'] = '';
            }
        } else {
            $migration['stub'] = 'normalmigrations';
            $migration['table_name'] = '';
        }
        return [
            'file_name' => strtolower(date('Y_m_d_hmi') . '_' . $migration_name),
            'table_name' => strtolower($this->pluralizeTableName($migration['table_name'], $table_name)),
            'stub' =>  $migration['stub']
        ];
    }

    public function pluralizeTableName($table_name, $default_table_name = '')
    {
        if (!empty($default_table_name)) {
            return $default_table_name;
        }

        if (empty($table_name)) {
            return null;
        }

        $last_letter = substr($table_name, -1);
        $last_two_letters = substr($table_name, -2);

        // Check for nouns ending in -s, -ss, -sh, -ch, -x, or -z
        if (in_array($last_two_letters, ['ss', 'sh', 'ch']) || in_array($last_letter, ['s', 'x', 'z'])) {
            return $table_name . 'es';
        }

        // Check for nouns ending in -y preceded by a consonant
        if ($last_letter === 'y' && !in_array(substr($table_name, -2, 1), ['a', 'e', 'i', 'o', 'u'])) {
            return substr($table_name, 0, -1) . 'ies';
        }

        // Check for nouns ending in -o (exceptions not handled for simplicity)
        if ($last_letter === 'o') {
            return $table_name . 'es';
        }

        // Default case: add 's'
        return $table_name . 's';
    }
}
?>
