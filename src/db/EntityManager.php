<?php


namespace jsomhorst\orm\db;


use Doctrine\ORM\Mapping\Entity;

class EntityManager
{
    public static function getInstance() : EntityManager{
        $entityManager = null;

        if ($entityManager === null)
        {
            $paths = array(__DIR__ . './entities');
            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths);

            $connectionParams = array(
                'driver'         => 'pdo_mysql',
                'user'           => 'root',
                'password'       => 'root',
                'host'           => 'localhost',
                'port'           =>  3306,
                'dbname'         => 'accounting',
                'charset'        => 'UTF-8',
            );


            $entityManager = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
        }

        return $entityManager;
    }
}