<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 17:30:32 +0800
 */
namespace fwkit\Weibo\Components;

use fwkit\Weibo\ComponentBase;

class Common extends ComponentBase
{
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

        $action = new Action($name, $this->actionList[$name]);
        $action->setClient($this->client);

        if (array_key_exists(0, $args)) {
            if (is_array($args[0])) {
                $action->withParams($args[0]);
            }
            return $action->execute();
        } else {
            return $action;
        }
    }
}
