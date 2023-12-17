<?php
namespace libs\Logsys;

use helpers\ConfigHelper;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Logs
{
    protected static $log;

    public static function init()
    {
        if (!isset(self::$log)) {
            $logPath = ConfigHelper::get('log.path');
            $filePath = ConfigHelper::get('log.file');
            echo $logPath;
            self::$log = new Logger('loggers');
            self::$log->pushHandler(new StreamHandler($logPath . $filePath, Level::Info));
        }
    }

    /**
     * @param string $logs
     * @return void
     */
    public static function info($logs)
    {
        self::init();
        self::$log->log('INFO', $logs);
    }

    /**
     * @param string $logs
     * @return void
     */
    public static function warning($logs)
    {
        self::init();
        self::$log->warning('Warning: ' . $logs);
    }

    /**
     * @param string $logs
     * @return void
     */
    public static function error($logs)
    {
        self::init();
        self::$log->error('Error: ' . $logs);
    }

    /**
     * @param string $logs
     * @return void
     */
    public static function debug($logs)
    {
        self::init();
        self::$log->debug('Debug: ' . $logs);
    }
}