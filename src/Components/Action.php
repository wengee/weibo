<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-10-10 15:58:32 +0800
 */
namespace fwkit\Weibo\Components;

use fwkit\Weibo\ComponentBase;
use fwkit\Weibo\Concerns\HasOptions;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

class Action extends ComponentBase
{
    use HasOptions;

    protected $name;

    protected $url;

    protected $method = 'GET';

    protected $accessToken = null;

    protected $params = [];

    protected $maps = [];

    protected $data = [];

    public function __construct(string $name, array $options)
    {
        $this->setOptions($options);
        $this->name = $name;
        $this->data = [];
    }

    public function __call(string $method, array $args)
    {
        $key = null;
        if (strpos($method, 'set') === 0) {
            $key = lcfirst(substr($method, 3));
        } elseif (strpos($method, 'with') === 0) {
            $key = lcfirst(substr($method, 4));
        }

        if ($key !== null) {
            $value = $args[0] ?? null;
            $this->withParam($key, $value);
        }

        return $this;
    }

    public function hasNoParams(): bool
    {
        return $this->params === false ? true : false;
    }

    public function withParam(string $key, $value)
    {
        if ($this->params === false) {
            return $this;
        }

        if ($this->maps && isset($this->maps[$key])) {
            $key = $this->maps[$key];
        }

        if (in_array($key, $this->params)) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public function withParams(array $params)
    {
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $this->withParam($key, $value);
            }
        }

        return $this;
    }

    public function execute()
    {
        $options = [];
        $method = strtoupper($this->method);
        if ($method === 'POST') {
            $multipart = [];
            foreach ($this->data as $key => $value) {
                if (is_string($value) && $value{0} === '@') {
                    $file = substr($value, 1);
                    $file = new Stream(fopen($file, 'r'));

                    $multipart[] = ['name' => $key, 'contents' => $file];
                    unset($this->data[$key]);
                } elseif ($value instanceof StreamInterface) {
                    $multipart[] = ['name' => $key, 'contents' => $value];
                    unset($this->data[$key]);
                }
            }

            $options['body'] = $this->data;
            if ($multipart) {
                $options['multipart'] = $multipart;
            }
        } else {
            $options['query'] = $this->data;
        }

        $res = $this->request($method, $this->url, $options, $this->accessToken);
        return $this->checkResponse($res);
    }
}
