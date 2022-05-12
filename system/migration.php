<?php
return array(
    'colors' => true,
    'databases' => array(
        'blindhub' => array(
            'database_dsn'      => "mysql:dbname={$_ENV['DB_DATABASE']};host={$_ENV['DB_HOST']}",
            'database_user'     => $_ENV['DB_USERNAME'],
            'database_password' => $_ENV['DB_PASSWORD'],

            // or
            // mysql client command settings.
            // 'mysql_command_enable'    => true,
            // 'mysql_command_cli'       => "/usr/bin/mysql",
            // 'mysql_command_tmpsqldir' => "/tmp",
            // 'mysql_command_host'      => "localhost",
            // 'mysql_command_user'      => "user",
            // 'mysql_command_password'  => "password",
            // 'mysql_command_database'  => "yourdatabase",
            // 'mysql_command_options'   => "--default-character-set=utf8",

            // schema version table
            'schema_version_table'    => 'schema_version',

            'migration_dir'           => './migrations'
        ),
    ),
);
