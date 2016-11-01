<?php

namespace Javiern\DAO;

use Doctrine\DBAL\Connection;

class BaseDAO
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * UserProfile constructor.
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        return $this->db;
    }
}
