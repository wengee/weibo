<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-10-10 15:57:29 +0800
 */
namespace fwkit\Weibo\Components;

use fwkit\Weibo\ComponentBase;

class Common extends ComponentBase
{
    protected static $actions = [];

    protected $name;

    protected $actionList = [];

    public function __construct(string $name, array $actionList)
    {
        $this->name = $name;
        $this->actionList = $actionList;
    }

    public function __call(string $name, array $args)
    {
        if (!isset($this->actionList[$name])) {
            throw new \Exception('The operation is undefined.');
        }

        if (!isset(self::$actions[$name])) {
            $action = new Action($name, $this->actionList[$name]);
            $action->setClient($this->client);
            self::$actions[$name] = $action;
        } else {
            $action = self::$actions[$name];
        }

        if (isset($args[0]) && is_array($args[0])) {
            return $action->withParams($args[0])->execute();
        } elseif (count($args) > 0 || $action->hasNoParams()) {
            return $action->execute();
        }

        return $action;
    }
}
