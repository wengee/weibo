<?php

namespace Weibo;

require_once(__DIR__.'/src/saetv2.ex.class.php');

class OAuth extends \SaeTOAuthV2 {
    private function tokenInfoURL() { return 'https://api.weibo.com/oauth2/get_token_info'; }

    public function getTokenInfo($accessToken)
    {
        $params = array();
        $params['client_id'] = $this->client_id;
        $params['client_secret'] = $this->client_secret;
        $params['access_token'] = $accessToken;

        $response = $this->oAuthRequest($this->tokenInfoURL(), 'POST', $params);
        $token = json_decode($response, true);
        if ( ! is_array($token)) {
            throw new OAuthException("get token info failed." . $token['error']);
        }
        return $token;
    }
}