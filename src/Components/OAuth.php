<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 16:56:20 +0800
 */
namespace fwkit\Weibo\Components;

use fwkit\Weibo\ComponentBase;

class OAuth extends ComponentBase
{
    public function authorizeUrl(string $url, string $state = null, ?string $scope = null, ?string $display = null, ?bool $forceLogin = null, ?string $language = null): string
    {
        $query = [
            'client_id' => $this->client->getClientId(),
            'redirect_uri' => $url,
            'state' => $state,
        ];
        if ($scope !== null) {
            $query['scope'] = $scope;
        }

        if ($display !== null) {
            $query['display'] = $display;
        }

        if ($forceLogin !== null) {
            $query['forcelogin'] = $forceLogin ? 1 : 0;
        }

        if ($language !== null) {
            $query['language'] = $language;
        }

        return 'https://api.weibo.com/oauth2/authorize?' . http_build_query($query);
    }

    public function getAccessToken(string $type = 'code', $arg1, $arg2)
    {
        $params = [
            'client_id' => $this->client->getClientId(),
            'client_secret' => $this->client->getClientSecret(),
        ];

        if ($type === 'token') {
            $params['grant_type'] = 'refresh_token';
            $params['refresh_token'] = $arg1;
        } elseif ($type === 'code') {
            $params['grant_type'] = 'authorization_code';
            $params['code'] = $arg1;
            $params['redirect_uri'] = $arg2;
        } elseif ($type === 'password') {
            $params['grant_type'] = 'password';
            $params['username'] = $arg1;
            $params['password'] = $arg2;
        } else {
            throw new \Exception('wrong auth type');
        }

        $res = $this->post('https://api.weibo.com/oauth2/access_token', [
            'body' => $params,
        ], false);

        return $this->checkResponse($res, [
            'access_token' => 'accessToken',
            'expires_in' => 'expiresIn',
        ]);
    }

    public function getTokenInfo(?string $accessToken = null)
    {
        $res = $this->post('https://api.weibo.com/oauth2/get_token_info', [], $accessToken);
        return $this->checkResponse($res, [
            'appkey' => 'appKey',
            'create_at' => 'created',
            'expire_in' => 'expiresIn',
        ]);
    }

    public function revoke(?string $accessToken = null)
    {
        $res = $this->post('https://api.weibo.com/oauth2/revokeoauth2', [], $accessToken);
        $this->checkResponse($res);
        return true;
    }
}
