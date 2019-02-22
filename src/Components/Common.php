<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 17:01:40 +0800
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

        $params = $args[0] ?? null;
        if ($params !== null) {
            if (is_array($params)) {
                $action->withParams($params);
            }
            return $action->execute();
        } else {
            return $action;
        }
    }
}
