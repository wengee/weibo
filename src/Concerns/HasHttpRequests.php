<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-11-17 16:29:03 +0800
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
            $client = new Client();
        }

        $method = strtoupper($method);
        if (!isset($options['base_uri']) && !preg_match('#^https?://.+#i', $url) && property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        if (false !== $accessToken) {
            $accessToken = $accessToken ?: $this->getAccessToken();

            $options['headers']                  = $options['headers'] ?? [];
            $options['headers']['Authorization'] = 'OAuth2 '.$accessToken;
        } else {
            $appKey           = $this->getClientId();
            $options['query'] = $options['query'] ?? [];
            if (is_array($options['query'])) {
                $options['query']['source'] = $appKey;
            } elseif (is_string($options['query'])) {
                $options['query'] = $options['query'].($options['query'] ? '&' : '?').'source='.$appKey;
            }
        }

        if (isset($options['withCert'])) {
            $withCert = $options['withCert'];
            unset($options['withCert']);

            if ($withCert) {
                $options['cert']    = $this->sslCert;
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
        if ('raw' === $dataType || empty($dataType)) {
            return $response;
        }

        $res  = null;
        $body = $response->getBody()->getContents();
        if ($body) {
            if ('xml' === $dataType || ('auto' === $dataType && '<' === $body[0])) {
                $backup = libxml_disable_entity_loader(true);
                $res    = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
                $res    = $res ? json_decode(json_encode($res), true) : null;

                libxml_disable_entity_loader($backup);
            } elseif ('json' === $dataType || 'auto' === $dataType) {
                $res = @json_decode($body, true);
            }
        }

        return ('auto' === $dataType) ? ($res ?: $response) : $res;
    }
}
