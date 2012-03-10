<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                // entity manager
                'dbal_migrations_config' => 'DoctrineMigrationsModule\Configuration',
            ),
            'dbal_migrations_config' => array(
                'parameters' => array(
                    'connection' => 'orm_connection',
                    'migrationsNamespace' => 'DoctrineMigrationsModule',
                    'migrationsDirectory' => realpath(__DIR__ . '/../../../data/DoctrineMigrationsModule'),
                )
            )
        )
    )
);