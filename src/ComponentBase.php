<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-08-14 14:18:59 +0800
 */
namespace fwkit\Weibo;

abstract class ComponentBase
{
    protected $client;

    public function setClient($client)
    {
        $this->client = $client;
    }

    protected function get(string $url, array $options = [], $accessToken = null, $dataType = 'auto')
    {
        return $this->request('GET', $url, $options, $accessToken);
    }

    protected function post(string $url, array $options = [], $accessToken = null, $dataType = 'auto')
    {
        return $this->request('POST', $url, $options, $accessToken);
    }

    protected function request(string $method, string $url, array $options = [], $accessToken = null, $dataType = 'auto')
    {
        return $this->client->request($method, $url, $options, $accessToken, $dataType);
    }

    protected function checkResponse($res, ?array $map = [], bool $strict = true)
    {
        if (is_array($res)) {
            if (!empty($res['error_code'])) {
                throw new OfficialError($res['error'], $res['error_code']);
            }

            if ($map && is_array($map)) {
                $res = $this->transformKeys($res, $map);
            }

            return $this->makeCollection($res);
        }

        if ($strict) {
            throw new OfficialError('An unknown error occurred.');
        }

        return $res;
    }

    protected function transformKeys(array $arr, array $map): array
    {
        $ret = [];
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $value = $this->transformKeys($value, $map);
            }

            if (isset($map[$key])) {
                $newKey = $map[$key];
                $ret[$newKey] = $value;
            } else {
                $ret[$key] = $value;
            }
        }

        return $ret;
    }

    protected function makeCollection($arr)
    {
        return new Collection($arr);
    }
}
