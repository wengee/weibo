<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-11-17 17:44:24 +0800
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

    protected $accessToken;

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
        if (0 === strpos($method, 'set')) {
            $key = lcfirst(substr($method, 3));
        } elseif (0 === strpos($method, 'with')) {
            $key = lcfirst(substr($method, 4));
        }

        if (null !== $key) {
            $value = $args[0] ?? null;
            $this->withParam($key, $value);
        }

        return $this;
    }

    public function hasNoParams(): bool
    {
        return false === $this->params ? true : false;
    }

    public function withParam(string $key, $value)
    {
        if (false === $this->params) {
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

    public function withParamList(array $params)
    {
        if (false === $this->params) {
            return $this;
        }

        $i      = 0;
        $params = array_values($params);
        $count  = count($params);
        foreach ($this->params as $key) {
            if ($i >= $count) {
                break;
            }

            $this->data[$key] = $params[$i];
            ++$i;
        }

        return $this;
    }

    public function execute()
    {
        $options = [];
        $method  = strtoupper($this->method);
        if ('POST' === $method) {
            $useMultipart = false;
            $multipart    = [];
            foreach ($this->data as $key => $value) {
                if (is_string($value) && '@' === $value[0]) {
                    $file  = substr($value, 1);
                    $value = new Stream(fopen($file, 'r'));
                }

                $multipart[] = ['name' => $key, 'contents' => $value];
                if ($value instanceof StreamInterface) {
                    $useMultipart = true;
                }
            }

            if ($useMultipart) {
                $options['multipart'] = $multipart;
            } else {
                unset($multipart);
                $options['form_params'] = $this->data;
            }
        } else {
            $options['query'] = array_filter($this->data, function ($item) {
                return !is_null($item);
            });
        }

        $res = $this->request($method, $this->url, $options, $this->accessToken);

        return $this->checkResponse($res);
    }
}
