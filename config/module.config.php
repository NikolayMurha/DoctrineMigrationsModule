<?php
return array(
    'doctrine' => array(
        'migrations' => array(
            'connection' => 'doctrine.connection.orm_default',
            // 'output_writer' => 'doctrine.migrations.output_writer',

            'migrations_table' => 'migrations',
            'migrations_namespace' => 'Application',
            'migrations_directory' => __DIR__ . '/../../../data/doctrine/migrations',
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