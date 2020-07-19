<?php

return [
    Doctrine\ORM\Mapping\Entity::class => function(){
        return \jsomhorst\orm\db\EntityManager::getInstance();
    }
];
