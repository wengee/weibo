<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 16:01:30 +0800
 */
namespace fwkit\Weibo\Concerns;

trait HasComponents
{
    protected $components = [];

    public function get(string $name)
    {
        return $this->component($name);
    }

    public function component(string $name)
    {
        if (isset($this->components[$name])) {
            return $this->components[$name];
        }

        $className = $this->componentList[$name] ?? null;
        if (is_array($className)) {
            $commonClass = property_exists($this, 'defaultComponent') ? $this->defaultComponent : null;
            if ($commonClass) {
                $component = new $commonClass($name, $className);
                $component->setClient($this);
                $this->components[$name] = $component;
                return $component;
            }
        } elseif ($className !== null) {
            $component = new $className;
            $component->setClient($this);
            $this->components[$name] = $component;
            return $component;
        }

        return null;
    }
}
