<?php

namespace MaasAds;

class User
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(\MVC\Framework\Database $db)
    {
       $this->db = $db;
    }
}