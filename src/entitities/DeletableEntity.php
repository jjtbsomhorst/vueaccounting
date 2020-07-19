<?php


namespace jsomhorst\orm\Entities;


class DeletableEntity
{
    /** @Column(type="string",length=36,options={"default":uuid()} */
    protected $id;

    /** @Column(type="boolean",options={"default":0} */
    protected $deleted;

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}