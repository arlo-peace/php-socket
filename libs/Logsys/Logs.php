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
            $subPath = ConfigHelper::get('log.sub');
            $fileType = ConfigHelper::get('log.type');
            $filePath = 'log.log';
            $subPaths = '';
            if($subPath=='month'){
                $subPaths = '/'.date('m-Y');
            } elseif($subPath=='date'){
                $subPaths = '/'.date('d-m-Y');
            }
            if($fileType==='day'){
                $filePath = '/'.date('d').'.log';
            } elseif($fileType==='file') {
                $filePath = '/'.$filePath;
            } elseif($fileType==='date') {
                $filePath = '/'.date('d-m-Y').'.log';
            }

            self::$log = new Logger('loggers');
            self::$log->pushHandler(new StreamHandler($logPath . $subPaths . $filePath, Level::Info));
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