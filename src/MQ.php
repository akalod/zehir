<?php
/**
 * Created by PhpStorm.
 * User: sTaRs
 * Date: 9/7/19
 * Time: 10:28
 */

namespace Zehir\System;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Zehir\Settings\Setup;

class MQ
{
    public static $channel = null;
    public static $connection = null;

    /**
     * @throws \AMQPException
     */
    public static function init()
    {
        $setting = Setup::getConnectionsSettings();
        if (!isset($setting['mq'])) {
            throw new \Exception("Kuyruk ayarları set edilmemiş", 1000);
        }
        $s = $setting['mq'];
        if (!isset($s['port']))
            $s['port'] = 5672;
        if (!isset($s['user'])) {
            $s['user'] = 'guest';
            $s['pass'] = 'guest';
        }
        if (!isset($s['host'])) {
            $s['host'] = 'localhost';
        }
        try {
            self::$connection = new AMQPStreamConnection($s['host'], $s['port'], $s['user'], $s['pass']);
        } catch (\AMQPException $e) {
            throw $e;
        }
        self::$channel = self::$connection->channel();
    }

    public static function add($command, $data)
    {
        if (!self::$channel)
            self::init();

        self::$channel->queue_declare($command, false, false, false, false);
        self::$channel->basic_publish(new AMQPMessage(json_encode($data,JSON_NUMERIC_CHECK)), '', $command);

    }

    public static function destruct()
    {
        if (self::$channel)
            self::$channel->close();
        try {
            if (self::$connection)
                self::$connection->close();
        }catch (\AMQPException $e){
            if(Setup::$target!='prod'){
                echo $e->getMessage();
            }
        }
    }
}