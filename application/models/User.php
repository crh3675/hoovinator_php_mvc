<?php

namespace Ads;

class User
{
    /**
     * Constructor, expects a Database connection
     * @param Database $db The Database object
     */
    public function __construct(\Database $db)
    {
       $this->db = $db;
    }
}