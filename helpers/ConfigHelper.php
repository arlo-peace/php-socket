<?php
namespace helpers;

/**
 *
 */
class ConfigHelper
{
    protected static $config;

    public static function loadConfig()
    {
        if (is_null(static::$config))
            static::$config = include(dirname(__DIR__).'/config/config.php');
    }

    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public static function get($key, $default=null)
    {
        static::loadConfig();

        $keys = explode('.', $key);
        $config = static::$config;

        foreach ($keys as $nestedKey) {
            if (isset($config[$nestedKey])) {
                $config = $config[$nestedKey];
            } else {
                return $default;
            }
        }

        return $config;
    }
}