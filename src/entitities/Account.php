<?php
namespace jsomhorst\orm\Entities;

class Account extends DeletableEntity
{
    /**
     * CREATE TABLE IF NOT EXISTS accounts (id VARCHAR(32)
     * PRIMARY KEY NOT NULL,
     * name VARCHAR(50) not null,
     * accountnumber VARCHAR(31),
     * userid VARCHAR(36),
     * deleted TINYINT);
     */

    /**
     * @Column(type="string",length=255);
     */
    protected $name;
    /**
     * @Column(type="string",length=40);
     */
    protected $accountnumber;
    /**
     * @Column(type="string",length=36);
     */
    protected $userid;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAccountnumber()
    {
        return $this->accountnumber;
    }

    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }


}