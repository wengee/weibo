<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2023-01-12 22:50:08 +0800
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

        $className = $this->getComponentConfig($name);
        if (is_array($className)) {
            $commonClass = property_exists($this, 'defaultComponent') ? $this->defaultComponent : null;
            if ($commonClass) {
                $component = new $commonClass($name, $className);
                $component->setClient($this);
                $this->components[$name] = $component;

                return $component;
            }
        } elseif (null !== $className) {
            $component = new $className();
            $component->setClient($this);
            $this->components[$name] = $component;

            return $component;
        }

        return null;
    }
}
