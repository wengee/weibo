<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-11-17 17:41:17 +0800
 */

namespace fwkit\Weibo\Components;

use fwkit\Weibo\ComponentBase;

class OAuth extends ComponentBase
{
    public function authorizeUrl(string $url, string $state = '', ?string $scope = null, ?string $display = null, ?bool $forceLogin = null, ?string $language = null): string
    {
        $query = [
            'client_id'    => $this->client->getClientId(),
            'redirect_uri' => $url,
            'state'        => $state,
        ];

        if (null !== $scope) {
            $query['scope'] = $scope;
        }

        if (null !== $display) {
            $query['display'] = $display;
        }

        if (null !== $forceLogin) {
            $query['forcelogin'] = $forceLogin ? 1 : 0;
        }

        if (null !== $language) {
            $query['language'] = $language;
        }

        return 'https://api.weibo.com/oauth2/authorize?'.http_build_query($query);
    }

    /**
     * Get access token.
     *
     * @param string $type 取值范围： token, code, password
     * @param string $arg1 type=token时为refresh_token, type=code时为code, type=password时为username
     * @param string $arg2 type=code时为redirect_uri, type=password时为password
     */
    public function getAccessToken(string $type = 'code', string $arg1 = '', string $arg2 = '')
    {
        $params = [
            'client_id'     => $this->client->getClientId(),
            'client_secret' => $this->client->getClientSecret(),
        ];

        if ('token' === $type) {
            $params['grant_type']    = 'refresh_token';
            $params['refresh_token'] = $arg1;
        } elseif ('code' === $type) {
            $params['grant_type']   = 'authorization_code';
            $params['code']         = $arg1;
            $params['redirect_uri'] = $arg2;
        } elseif ('password' === $type) {
            $params['grant_type'] = 'password';
            $params['username']   = $arg1;
            $params['password']   = $arg2;
        } else {
            throw new \Exception('wrong auth type');
        }

        $res = $this->post('https://api.weibo.com/oauth2/access_token', [
            'form_params' => $params,
        ], false);

        return $this->checkResponse($res, [
            'access_token' => 'accessToken',
            'expires_in'   => 'expiresIn',
        ]);
    }

    public function getTokenInfo(?string $accessToken = null)
    {
        $accessToken = $accessToken ?: $this->client->getAccessToken();
        $res         = $this->post('https://api.weibo.com/oauth2/get_token_info', [
            'form_params' => ['access_token' => $accessToken],
        ], false);

        return $this->checkResponse($res, [
            'appkey'    => 'appKey',
            'create_at' => 'created',
            'expire_in' => 'expiresIn',
        ]);
    }

    public function revoke(?string $accessToken = null)
    {
        $accessToken = $accessToken ?: $this->client->getAccessToken();
        $res         = $this->post('https://api.weibo.com/oauth2/revokeoauth2', [
            'form_params' => ['access_token' => $accessToken],
        ], false);
        $this->checkResponse($res);

        return true;
    }
}
