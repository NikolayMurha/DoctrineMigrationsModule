<?php

namespace DoctrineMigrationsModule\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineMigrationsModule\Migrations\Configuration;

/**
 * Created by IntelliJ IDEA.
 * User: Nikolay
 * Date: 17.01.13
 * Time: 16:00
 * To change this template use File | Settings | File Templates.
 */


class ConfigurationFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $config = $config['doctrine']['migrations'];

        if (isset($config['connection']) && $serviceLocator->has($config['connection'])) {
            $connection = $serviceLocator->get($config['connection']);
        } else {
            $connection = $serviceLocator->get('doctrine.connection.orm_default');
        }
        unset($config['connection']);

        if (isset($config['output_writer']) && $serviceLocator->has($config['output_writer'])) {
            $outputWriter = $serviceLocator->get($config['output_writer']);
        } else {
            $outputWriter = null;
        }
        unset($config['output_writer']);

        $configuration = new Configuration($connection, $outputWriter);

        foreach ($config as $key => $value) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (!method_exists($configuration, $setter)) {
                continue;
            }
            $configuration->{$setter}($value);
        }

        return $configuration;
    }
}
