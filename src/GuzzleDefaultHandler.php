<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 10:51:01 +0800
 */
namespace fwkit\Weibo;

class GuzzleDefaultHandler
{
    /** @var string|callable|null */
    private static $defaultHandler;

    /**
     * Set a default handler
     *
     * @param string|callable|null $handler class name or callable. If value is null, that has no default handler
     * @return void
     */
    public static function setDefaultHandler($handler)
    {
        if (is_callable($handler)) {
            self::$defaultHandler = $handler;
        } elseif (is_string($handler) && class_exists($handler)) {
            self::$defaultHandler = new $handler;
        }
    }

    /**
     * Get default handler
     *
     * If return null, that has no default handler
     *
     * @return string|callable|null
     */
    public static function getDefaultHandler()
    {
        return static::$defaultHandler ?: null;
    }
}