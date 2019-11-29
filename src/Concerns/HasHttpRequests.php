<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-11-29 18:48:14 +0800
 */
namespace fwkit\Weibo\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

trait HasHttpRequests
{
    public function request(string $method, string $url, array $options, $accessToken = null, $dataType = 'auto')
    {
        static $client;
        if (!isset($client)) {
            $client = new Client;
        }

        $method = strtoupper($method);
        if (!isset($options['base_uri']) && !preg_match('#^https?://.+#i', $url) && property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        if ($accessToken !== false) {
            $accessToken = $accessToken ?: $this->getAccessToken();

            $options['headers'] = $options['headers'] ?? [];
            $options['headers']['Authorization'] = 'OAuth2 ' . $accessToken;
        } else {
            $appKey = $this->getClientId();
            $options['query'] = $options['query'] ?? [];
            if (is_array($options['query'])) {
                $options['query']['source'] = $appKey;
            } elseif (is_string($options['query'])) {
                $options['query'] = $options['query'] . ($options['query'] ? '&' : '?') . 'source=' . $appKey;
            }
        }

        if (isset($options['withCert'])) {
            $withCert = $options['withCert'];
            unset($options['withCert']);

            if ($withCert) {
                $options['cert'] = $this->sslCert;
                $options['ssl_key'] = $this->sslKey;
            }
        }

        if (isset($options['json'])) {
            $options['body'] = json_encode($options['json'], JSON_UNESCAPED_UNICODE);
            unset($options['json']);
        }

        $response = $client->request($method, $url, $options);
        return $this->parseResponse($response);
    }

    protected function parseResponse(Response $response, $dataType = 'auto')
    {
        if ($dataType === 'raw' || empty($dataType)) {
            return $response;
        }

        $res = null;
        $body = trim($response->getBody());
        if ($body) {
            if ($dataType === 'xml' || ($dataType === 'auto' && $body{0} === '<')) {
                $backup = libxml_disable_entity_loader(true);
                $res = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
                $res = $res ? json_decode(json_encode($res), true) : null;

                libxml_disable_entity_loader($backup);
            } elseif ($dataType === 'json' || $dataType === 'auto') {
                $res = @json_decode($body, true);
            }
        }

        return ($dataType === 'auto') ? ($res ?: $response) : $res;
    }
}
