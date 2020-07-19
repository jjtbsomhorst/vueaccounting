<?php


namespace jsomhorst\orm\Entities;

/**
 * Class User
 * @package jsomhorst\orm\Entities
 * @Entity @Table(name='user')
 */
class User extends DeletableEntity
{

    /** @Column(type="string",length=255",nullable=false) */
    protected $username;
    /** @Column(type="string",length=255",nullable=false) */
    protected $password;


    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }




}