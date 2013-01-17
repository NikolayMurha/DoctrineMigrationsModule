<?php

$dataDir = __DIR__ . '/../../../../data';
if (!is_dir($dataDir)) {
    $dataDir = __DIR__ . '/../../../data';
}

return array(
    'doctrine' => array(
        'migrations' => array(
            'connection' => 'doctrine.connection.orm_default',
            // 'output_writer' => 'doctrine.migrations.output_writer',

            'migrations_table' => 'migrations',
            'migrations_namespace' => 'Application',
            'migrations_directory' => $dataDir . '/migrations',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'DoctrineMigrationsModule\Configuration' => 'doctrine.migrations.configuration',
        ),
        /*
        'invokables' => array(
            'doctrine.migrations.output_writer'  => 'Doctrine\DBAL\Migrations\OutputWriter',
        ),
        */
        'factories' => array(
            'doctrine.migrations.configuration'  => 'DoctrineMigrationsModule\Service\ConfigurationFactory',
        ),
    )
);