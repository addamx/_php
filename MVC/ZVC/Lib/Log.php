<?php
namespace Lib;

class Log
{
    const LOGFILE = 'curr.log';

    public static function write($cont)
    {
        $cont = date("Ymd H:i:s", time()) . "\r\n" . $cont . "\r\n";
        $log  = self::isBak();
        $fh   = fopen($log, 'ab');
        fwrite($fh, $cont);
        fclose($fh);
    }

    public static function bak()
    {
        $log = ROOT . '/log/' . self::LOGFILE;
        $bak = ROOT . '/log/' . date('ymd') . "_" . mt_rand(10000, 99999) . '.bak';
        return rename($log, $bak);
    }

    public static function isBak()
    {
        $log = ROOT . '/log/' . self::LOGFILE;
        if (!file_exists($log)) {
            touch($log);
            return $log;
        }

        clearstatcache(true, $log);
        if (filesize($log) <= 1024 * 1024) {
            return $log;
        }

        if (!self::Bak()) {
            return $log;
        } else {
            touch($log);
            return $log;
        }

    }

}
