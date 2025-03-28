<?php

namespace App\Models;

class Model
{
    // Static property to hold the database connection
    protected static $db;

    /**
     * Initialise the database connection.
     * If the connection is not already established, it loads the configuration
     * and sets up the connection.
     */
    public static function init()
    {
        if (self::$db === null) {
            // Require the database configuration file and establish the connection
            self::$db = require_once __DIR__ . '/../../config/database.php';
        }
    }

    /**
     * Retrieve the database connection.
     * Ensures the connection is initialised before returning it.
     *
     * @return mixed The database connection instance
     */
    protected static function getDB()
    {
        self::init(); // Ensure the database connection is initialised
        return self::$db; // Return the database connection
    }
}
