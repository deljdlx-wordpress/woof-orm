<?php

namespace Woof\ORM;

use Illuminate\Database\Capsule\Manager;
use Woof\Model\Wordpress\Database as WordpressDatabase;

class ORM
{
    public static $prefix;


    /**
     * Eloquent manager
     * @var Manager
     */

    private $driver;


    /**
     * Wordpress database access
     *
     * @var WordpressDatabase
     */
    private $wpdb;


    protected static $instance;


    public static function getInstance()
    {
        if(static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __construct()
    {
        $this->wpdb = WordpressDatabase::getInstance();

        static::$prefix = $this->wpdb->getWpdb()->prefix;

        $this->driver = new Manager();

        // IMPORTANT eloquent initialization
        $this->driver->addConnection(
            [
                'driver' => 'mysql',
                'host' => \DB_HOST,
                'database' => \DB_NAME,
                'username' => \DB_USER,
                'password' => \DB_PASSWORD,
                'charset' => $this->wpdb->getWpdb()->charset,
                // 'prefix' => $this->wordpressDriver->prefix
                //'collation' => $this->wordpressDriver->collate,

            ],
            'default'
        );

        $this->driver->setAsGlobal();
        $this->driver->bootEloquent();

    }


    public function createTable($tableName, $descriptorFunction)
    {
        return $this->driver->schema()->create($tableName, $descriptorFunction);
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getWpDb()
    {
        return $this->wpdb;
    }
}
