<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-22 16:58:19 +0800
 */
namespace fwkit\Weibo;

use fwkit\Weibo\Concerns\HasComponents;
use fwkit\Weibo\Concerns\HasHttpRequests;
use fwkit\Weibo\Concerns\HasOptions;

class Client
{
    use HasHttpRequests, HasOptions, HasComponents;

    protected $baseUri = 'https://api.weibo.com/2/';

    protected $defaultComponent = Components\Common::class;

    protected $componentList = [
        'oauth' => Components\OAuth::class,

        'status' => [
            'homeTimeline' => [
                'url' => 'statuses/home_timeline.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user'],
            ],
            'publicTimeline' => [
                'url' => 'statuses/public_timeline.json',
                'params' => ['count', 'page', 'base_app'],
            ],
            'userTimeline' => [
                'url' => 'statuses/user_timeline.json',
                'params' => ['uid', 'screen_name', 'since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user'],
            ],
            'repostTimeline' => [
                'url' => 'statuses/repost_timeline.json',
                'params' => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
            ],
            'mentions' => [
                'url' => 'statuses/mentions.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source', 'filter_by_type'],
            ],
            'show' => [
                'url' => 'statuses/show.json',
                'params' => ['id'],
            ],
            'count' => [
                'url' => 'statuses/count.json',
                'params' => ['ids'],
            ],
            'share' => [
                'url' => 'statuses/share.json',
                'method' => 'POST',
                'params' => ['status', 'pic', 'rip'],
            ],
        ],

        'emotion' => [
            'fetchAll' => [
                'url' => 'emotions.json',
                'params' => ['type', 'language'],
            ],
        ],

        'comment' => [
            'show' => [
                'url' => 'comments/show.json',
                'params' => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
            ],
            'byMe' => [
                'url' => 'comments/by_me.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_source'],
            ],
            'toMe' => [
                'url' => 'comments/to_me.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source'],
            ],
            'timeline' => [
                'url' => 'comments/timeline.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'trim_user'],
            ],
            'mentions' => [
                'url' => 'comments/mentions.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source'],
            ],
            'showBatch' => [
                'url' => 'comments/show_batch.json',
                'params' => ['cids'],
            ],
            'create' => [
                'url' => 'comments/create.json',
                'method' => 'POST',
                'params' => ['comment', 'id', 'comment_ori', 'rip'],
            ],
            'destroy' => [
                'url' => 'comments/destroy.json',
                'method' => 'POST',
                'params' => ['cid'],
            ],
            'destroyBatch' => [
                'url' => 'comments/destroy_batch.json',
                'method' => 'POST',
                'params' => ['cids'],
            ],
            'reply' => [
                'url' => 'comments/reply.json',
                'method' => 'POST',
                'params' => ['cid', 'id', 'comment', 'without_mention', 'comment_ori', 'rip'],
            ],
        ],

        'user' => [
            'show' => [
                'url' => 'users/show.json',
                'params' => ['uid', 'screen_name'],
            ],
            'domainShow' => [
                'url' => 'users/domain_show.json',
                'params' => ['domain'],
            ],
            'counts' => [
                'url' => 'users/counts.json',
                'params' => ['uids'],
            ],
        ],

        'friendShip' => [
            'friends' => [
                'url' => 'friendships/friends.json',
                'params' => ['uid', 'screen_name', 'count', 'cursor', 'trim_status'],
            ],
            'friendIds' => [
                'url' => 'friendships/friends/ids.json',
                'params' => ['uid', 'screen_name', 'count', 'cursor'],
            ],
            'followers' => [
                'url' => 'friendships/followers.json',
                'params' => ['uid', 'screen_name', 'count', 'cursor', 'trim_status'],
            ],
            'followerIds' => [
                'url' => 'friendships/followers/ids.json',
                'params' => ['uid', 'screen_name', 'count', 'cursor'],
            ],
            'show' => [
                'url' => 'friendships/show.json',
                'params' => ['source_id', 'source_screen_name', 'target_id', 'target_screen_name'],
            ],
        ],

        'search' => [
            'topics' => [
                'url' => 'search/topics.json',
                'params' => ['q', 'count', 'page'],
            ],
        ],

        'url' => [
            'shorten' => [
                'url' => 'short_url/shorten.json',
                'params' => ['url_long'],
            ],
            'expand' => [
                'url' => 'short_url/expand.json',
                'params' => ['url_short'],
            ],
            'shareCounts' => [
                'url' => 'short_url/share/counts.json',
                'params' => ['url_short'],
            ],
            'commentCounts' => [
                'url' => 'short_url/comment/counts.json',
                'params' => ['url_short'],
            ],
        ],

        'common' => [
            'codeToLocation' => [
                'url' => 'common/code_to_location.json',
                'accessToken' => false,
                'params' => ['codes'],
            ],
            'getCity' => [
                'url' => 'common/get_city.json',
                'accessToken' => false,
                'params' => ['provicne', 'capital', 'language'],
            ],
            'getProvince' => [
                'url' => 'common/get_province.json',
                'accessToken' => false,
                'params' => ['country', 'capital', 'language'],
            ],
            'getCountry' => [
                'url' => 'common/get_country.json',
                'accessToken' => false,
                'params' => ['capital', 'language'],
            ],
            'getTimezone' => [
                'url' => 'common/get_timezone.json',
                'accessToken' => false,
                'params' => ['language'],
            ],
        ],

        'account' => [
            'rateLimitStatus' => [
                'url' => 'account/rate_limit_status.json',
            ],
            'getUid' => [
                'url' => 'account/get_uid.json',
            ],
            'getEmail' => [
                'url' => 'account/profile/email.json',
            ],
        ],
    ];

    protected $clientId;

    protected $clientSecret;

    protected $accessToken;

    public function __construct(array $options)
    {
        $this->setOptions($options);
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    public function setAccessToken(?string $accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }
}
