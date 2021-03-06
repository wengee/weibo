<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-11-08 11:52:49 +0800
 */
namespace fwkit\Weibo\Components;

use fwkit\Weibo\ComponentBase;

class Common extends ComponentBase
{
    protected $actions = [];

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

        if (!isset($this->actions[$name])) {
            $action = new Action($name, $this->actionList[$name]);
            $action->setClient($this->client);
            $this->actions[$name] = $action;
        } else {
            $action = $this->actions[$name];
        }

        if (count($args) === 1 && is_array($args[0])) {
            return $action->withParams($args[0])->execute();
        } elseif (count($args) > 0) {
            return $action->withParamList($args)->execute();
        } elseif ($action->hasNoParams()) {
            return $action->execute();
        }

        return $action;
    }
}
