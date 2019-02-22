<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 10:51:29 +0800
 */
use fwkit\Weibo\GuzzleDefaultHandler;

if (!function_exists('weibo_guzzle_handler')) {
    function weibo_guzzle_handler($handler)
    {
        return GuzzleDefaultHandler::setDefaultHandler($handler);
    }
}
