<?php

namespace SocketAPP\Provider;

use helpers\ConfigHelper;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

class DatabaseProvider
{
    /**
     * @throws \Exception
     */
    public static function init()
    {
        $driver = ConfigHelper::get('database.driver');
        $host = ConfigHelper::get('database.host');
        $database = ConfigHelper::get('database.database');
        $username = ConfigHelper::get('database.username');
        $password = ConfigHelper::get('database.password');
        $charset = ConfigHelper::get('database.charset');
        $collation = ConfigHelper::get('database.collation');
        $prefix = ConfigHelper::get('database.prefix');

        $container = new Container();
        $capsule = new Capsule();
        $availableSQL = ['mysql', 'postgres', 'sqlite', 'sqlsrv'];
        if(!$driver & !$host & !$database){
            throw new \ValueError('You need this driver field.');
        }

        if(!in_array(strtolower($driver), $availableSQL)) {
            throw new \Exception('Database Support: This database driver do not support.', '6000');
        }

        $capsule->addConnection([
            'driver'    => $driver,
            'host'      => $host,
            'database'  => $database,
            'username'  => $username,
            'password'  => $password,
            'charset'   => $charset?$charset:'utf8',
            'collation' => $collation?$collation:'utf8_unicode_ci',
            'prefix'    => $prefix,
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        try {
            Capsule::connection()->getPdo();
        } catch (\Exception $e){
            die("Could not connect to the database. Error: " . $e->getMessage());
        }
    }
}