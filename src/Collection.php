<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-08-14 14:19:20 +0800
 */

namespace fwkit\Weibo;

use ArrayObject;

class Collection extends ArrayObject
{
    public function __construct(array $input = [])
    {
        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);
    }

    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}
