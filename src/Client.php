<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-11-23 17:58:00 +0800
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
                'url'       => 'statuses/home_timeline.json',
                'params'    => ['since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user'],
                'maps'      => [
                    'sinceId'   => 'since_id',
                    'maxId'     => 'max_id',
                    'baseApp'   => 'base_app',
                    'trimUser'  => 'trim_user',
                ],
            ],
            'publicTimeline' => [
                'url'       => 'statuses/public_timeline.json',
                'params'    => ['count', 'page', 'base_app'],
                'maps'      => ['baseApp' => 'base_app'],
            ],
            'userTimeline' => [
                'url'       => 'statuses/user_timeline.json',
                'params'    => ['uid', 'screen_name', 'since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user'],
                'maps'      => [
                    'userId'        => 'uid',
                    'screenName'    => 'screen_name',
                    'sinceId'       => 'since_id',
                    'maxId'         => 'max_id',
                    'baseApp'       => 'base_app',
                    'trimUser'      => 'trim_user',
                ],
            ],
            'repostTimeline' => [
                'url'       => 'statuses/repost_timeline.json',
                'params'    => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
                'maps'      => [
                    'sinceId'           => 'since_id',
                    'maxId'             => 'max_id',
                    'filterByAuthor'    => 'filter_by_author',
                ],
            ],
            'mentions' => [
                'url'       => 'statuses/mentions.json',
                'params'    => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source', 'filter_by_type'],
                'maps'      => [
                    'sinceId'           => 'since_id',
                    'maxId'             => 'max_id',
                    'filterByAuthor'    => 'filter_by_author',
                    'filterBySource'    => 'filter_by_source',
                    'filterByType'      => 'filter_by_type',
                ],
            ],
            'show' => [
                'url'       => 'statuses/show.json',
                'params'    => ['id'],
            ],
            'count' => [
                'url'       => 'statuses/count.json',
                'params'    => ['ids'],
            ],
            'share' => [
                'url'       => 'statuses/share.json',
                'method'    => 'POST',
                'params'    => ['status', 'pic', 'rip'],
                'maps'      => ['remoteIp' => 'rip'],
            ],
        ],

        'emotion' => [
            'fetchAll' => [
                'url'           => 'emotions.json',
                'accessToken'   => false,
                'params'        => ['type', 'language'],
            ],
        ],

        'comment' => [
            'show' => [
                'url'       => 'comments/show.json',
                'params'    => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
                'maps'      => [
                    'sinceId'           => 'since_id',
                    'maxId'             => 'max_id',
                    'filterByAuthor'    => 'filter_by_author',
                ],
            ],
            'byMe' => [
                'url'       => 'comments/by_me.json',
                'params'    => ['since_id', 'max_id', 'count', 'page', 'filter_by_source'],
                'maps'      => [
                    'sinceId'           => 'since_id',
                    'maxId'             => 'max_id',
                    'filterBySource'    => 'filter_by_source',
                ],
            ],
            'toMe' => [
                'url'       => 'comments/to_me.json',
                'params'    => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source'],
                'maps'      => [
                    'sinceId'           => 'since_id',
                    'maxId'             => 'max_id',
                    'filterByAuthor'    => 'filter_by_author',
                    'filterBySource'    => 'filter_by_source',
                ],
            ],
            'timeline' => [
                'url'       => 'comments/timeline.json',
                'params'    => ['since_id', 'max_id', 'count', 'page', 'trim_user'],
                'maps'      => [
                    'sinceId'   => 'since_id',
                    'maxId'     => 'max_id',
                    'trimUser'  => 'trim_user',
                ],
            ],
            'mentions' => [
                'url'       => 'comments/mentions.json',
                'params'    => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source'],
                'maps'      => [
                    'sinceId'           => 'since_id',
                    'maxId'             => 'max_id',
                    'filterByAuthor'    => 'filter_by_author',
                    'filterBySource'    => 'filter_by_source',
                ],
            ],
            'showBatch' => [
                'url'       => 'comments/show_batch.json',
                'params'    => ['cids'],
                'maps'      => ['commentIds' => 'cids'],
            ],
            'create' => [
                'url'       => 'comments/create.json',
                'method'    => 'POST',
                'params'    => ['comment', 'id', 'comment_ori', 'rip'],
                'maps'      => [
                    'commentOri'    => 'comment_ori',
                    'remoteIp'      => 'rip',
                ],
            ],
            'destroy' => [
                'url'       => 'comments/destroy.json',
                'method'    => 'POST',
                'params'    => ['cid'],
                'maps'      => ['commentId' => 'cid'],
            ],
            'destroyBatch' => [
                'url'       => 'comments/destroy_batch.json',
                'method'    => 'POST',
                'params'    => ['cids'],
                'maps'      => ['commentIds' => 'cids'],
            ],
            'reply' => [
                'url'       => 'comments/reply.json',
                'method'    => 'POST',
                'params'    => ['cid', 'id', 'comment', 'without_mention', 'comment_ori', 'rip'],
                'maps'      => [
                    'commentId'         => 'cid',
                    'withoutMention'    => 'without_mention',
                    'commentOri'        => 'comment_ori',
                    'remoteIp'          => 'rip',
                ],
            ],
        ],

        'user' => [
            'show' => [
                'url'       => 'users/show.json',
                'params'    => ['uid', 'screen_name'],
                'maps'      => [
                    'id'            => 'uid',
                    'userId'        => 'uid',
                    'screenName'    => 'screen_name',
                ],
            ],
            'domainShow' => [
                'url'       => 'users/domain_show.json',
                'params'    => ['domain'],
            ],
            'counts' => [
                'url'       => 'users/counts.json',
                'params'    => ['uids'],
                'maps'      => [
                    'ids'       => 'uids',
                    'userIds'   => 'uids',
                ],
            ],
        ],

        'friendShip' => [
            'friends' => [
                'url'       => 'friendships/friends.json',
                'params'    => ['uid', 'screen_name', 'count', 'cursor', 'trim_status'],
                'maps'      => [
                    'userId'        => 'uid',
                    'screenName'    => 'screen_name',
                    'trimStatus'    => 'trim_status',
                ],
            ],
            'friendIds' => [
                'url'       => 'friendships/friends/ids.json',
                'params'    => ['uid', 'screen_name', 'count', 'cursor'],
                'maps'      => [
                    'userId'        => 'uid',
                    'screenName'    => 'screen_name',
                ],
            ],
            'followers' => [
                'url'       => 'friendships/followers.json',
                'params'    => ['uid', 'screen_name', 'count', 'cursor', 'trim_status'],
                'maps'      => [
                    'userId'        => 'uid',
                    'screenName'    => 'screen_name',
                    'trimStatus'    => 'trim_status',
                ],
            ],
            'followerIds' => [
                'url'       => 'friendships/followers/ids.json',
                'params'    => ['uid', 'screen_name', 'count', 'cursor'],
                'maps'      => [
                    'userId'        => 'uid',
                    'screenName'    => 'screen_name',
                ],
            ],
            'show' => [
                'url'       => 'friendships/show.json',
                'params'    => ['source_id', 'source_screen_name', 'target_id', 'target_screen_name'],
                'maps'      => [
                    'sourceId'          => 'source_id',
                    'sourceScreenName'  => 'source_screen_name',
                    'targetId'          => 'target_id',
                    'targetScreenName'  => 'target_screen_name',
                ],
            ],
        ],

        'search' => [
            'topics' => [
                'url'       => 'search/topics.json',
                'params'    => ['q', 'count', 'page'],
            ],
        ],

        'url' => [
            'shorten' => [
                'url'           => 'short_url/shorten.json',
                'accessToken'   => false,
                'params'        => ['url_long'],
                'maps'          => ['urlLong' => 'url_long'],
            ],
            'expand' => [
                'url'           => 'short_url/expand.json',
                'accessToken'   => false,
                'params'        => ['url_short'],
                'maps'          => ['urlShort' => 'url_short'],
            ],
            'shareCounts' => [
                'url'           => 'short_url/share/counts.json',
                'accessToken'   => false,
                'params'        => ['url_short'],
                'maps'          => ['urlShort' => 'url_short'],
            ],
            'commentCounts' => [
                'url'           => 'short_url/comment/counts.json',
                'accessToken'   => false,
                'params'        => ['url_short'],
                'maps'          => ['urlShort' => 'url_short'],
            ],
        ],

        'common' => [
            'codeToLocation' => [
                'url'           => 'common/code_to_location.json',
                'accessToken'   => false,
                'params'        => ['codes'],
            ],
            'getCity' => [
                'url'           => 'common/get_city.json',
                'accessToken'   => false,
                'params'        => ['provicne', 'capital', 'language'],
            ],
            'getProvince' => [
                'url'           => 'common/get_province.json',
                'accessToken'   => false,
                'params'        => ['country', 'capital', 'language'],
            ],
            'getCountry' => [
                'url'           => 'common/get_country.json',
                'accessToken'   => false,
                'params'        => ['capital', 'language'],
            ],
            'getTimezone' => [
                'url'           => 'common/get_timezone.json',
                'accessToken'   => false,
                'params'        => ['language'],
            ],
        ],

        'account' => [
            'rateLimitStatus' => [
                'url'       => 'account/rate_limit_status.json',
                'params'    => false,
            ],
            'getUid' => [
                'url'       => 'account/get_uid.json',
                'params'    => false,
            ],
            'getEmail' => [
                'url'       => 'account/profile/email.json',
                'params'    => false,
            ],
        ],

        'bizStatus' => [
            'repost' => [
                'url'       => 'https://c.api.weibo.com/2/statuses/repost/biz.json',
                'params'    => ['id', 'status', 'is_comment', 'rip'],
                'maps'      => [
                    'isComment' => 'is_comment',
                    'remoteIp'  => 'rip',
                ],
            ],
            'destroy' => [
                'url'       => 'https://c.api.weibo.com/2/statuses/destroy/biz.json',
                'params'    => ['id'],
            ],
            'update' => [
                'url'       => 'https://c.api.weibo.com/2/statuses/update/biz.json',
                'params'    => ['status', 'visible', 'list_id', 'lat', 'long', 'annotations', 'rip', 'is_longtext', 'custom_source'],
                'maps'      => [
                    'listId'        => 'list_id',
                    'latitude'      => 'lat',
                    'longitude'     => 'long',
                    'remoteIp'      => 'rip',
                    'isLongtext'    => 'is_longtext',
                    'customSource'  => 'custom_source',
                ],
            ],
            'upload' => [
                'url'       => 'https://c.api.weibo.com/2/statuses/upload/biz.json',
                'params'    => ['status', 'visible', 'list_id', 'pic', 'lat', 'long', 'annotations', 'rip'],
                'maps'      => [
                    'listId'        => 'list_id',
                    'latitude'      => 'lat',
                    'longitude'     => 'long',
                    'remoteIp'      => 'rip',
                ],
            ],
            'uploadUrlText' => [
                'url'       => 'https://c.api.weibo.com/2/statuses/upload_url_text/biz.json',
                'params'    => ['status', 'visible', 'list_id', 'url', 'pic_id', 'lat', 'long', 'annotations', 'rip', 'is_longtext'],
                'maps'      => [
                    'listId'        => 'list_id',
                    'picId'         => 'pic_id',
                    'latitude'      => 'lat',
                    'longitude'     => 'long',
                    'remoteIp'      => 'rip',
                    'isLongtext'    => 'is_longtext',
                ],
            ],
            'uploadPic' => [
                'url'       => 'https://c.api.weibo.com/2/statuses/upload_pic/biz.json',
                'params'    => ['pic'],
            ],
        ],

        'bizComment' => [
            'create' => [
                'url'       => 'https://c.api.weibo.com/2/comments/create/biz.json',
                'params'    => ['comment', 'id', 'comment_ori', 'rip'],
                'maps'      => [
                    'commentOri'    => 'comment_ori',
                    'remoteIp'      => 'rip',
                ],
            ],
            'destroy' => [
                'url'       => 'https://c.api.weibo.com/2/comments/destroy/biz.json',
                'params'    => ['cid'],
                'maps'      => ['commentId' => 'cid'],
            ],
            'reply' => [
                'url'       => 'https://c.api.weibo.com/2/comments/reply/biz.json',
                'params'    => ['cid', 'id', 'comment', 'without_mention', 'comment_ori', 'rip'],
                'maps'      => [
                    'commentId'         => 'cid',
                    'withoutMention'    => 'without_mention',
                    'commentOri'        => 'comment_ori',
                    'remoteIp'          => 'rip',
                ],
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
