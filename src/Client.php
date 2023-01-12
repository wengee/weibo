<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2023-01-12 22:51:38 +0800
 */

namespace fwkit\Weibo;

use fwkit\Weibo\Concerns\HasComponents;
use fwkit\Weibo\Concerns\HasHttpRequests;
use fwkit\Weibo\Concerns\HasOptions;

class Client
{
    use HasHttpRequests;
    use HasOptions;
    use HasComponents;

    protected $baseUri = 'https://api.weibo.com/2/';

    protected $defaultComponent = Components\Common::class;

    protected $componentList = [
        'oauth' => Components\OAuth::class,

        'status' => [
            'homeTimeline' => [
                'url'    => 'statuses/home_timeline.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user'],
                'maps'   => [
                    'sinceId'  => 'since_id',
                    'maxId'    => 'max_id',
                    'baseApp'  => 'base_app',
                    'trimUser' => 'trim_user',
                ],
            ],
            'userTimeline' => [
                'url'    => 'statuses/user_timeline.json',
                'params' => ['uid', 'screen_name', 'since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user'],
                'maps'   => [
                    'userId'     => 'uid',
                    'screenName' => 'screen_name',
                    'sinceId'    => 'since_id',
                    'maxId'      => 'max_id',
                    'baseApp'    => 'base_app',
                    'trimUser'   => 'trim_user',
                ],
            ],
            'repostTimeline' => [
                'url'    => 'statuses/repost_timeline.json',
                'params' => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                ],
            ],
            'mentions' => [
                'url'    => 'statuses/mentions.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source', 'filter_by_type'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                    'filterBySource' => 'filter_by_source',
                    'filterByType'   => 'filter_by_type',
                ],
            ],
            'show' => [
                'url'    => 'statuses/show.json',
                'params' => ['id'],
            ],
            'count' => [
                'url'    => 'statuses/count.json',
                'params' => ['ids'],
            ],
            'share' => [
                'url'    => 'statuses/share.json',
                'method' => 'POST',
                'params' => ['status', 'pic', 'rip'],
                'maps'   => ['remoteIp' => 'rip'],
            ],
            'queryId' => [
                'url'    => 'statuses/queryid.json',
                'params' => ['mid', 'type', 'is_batch', 'inbox', 'isBase62'],
                'maps'   => [
                    'isBatch' => 'is_batch',
                ],
            ],
            'queryMid' => [
                'url'    => 'statuses/querymid.json',
                'params' => ['id', 'type', 'is_batch'],
                'maps'   => [
                    'isBatch' => 'is_batch',
                ],
            ],
        ],

        'emotion' => [
            'fetchAll' => [
                'url'         => 'emotions.json',
                'accessToken' => false,
                'params'      => ['type', 'language'],
            ],
        ],

        'comment' => [
            'show' => [
                'url'    => 'comments/show.json',
                'params' => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                ],
            ],
            'mentions' => [
                'url'    => 'comments/mentions.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                    'filterBySource' => 'filter_by_source',
                ],
            ],
            'create' => [
                'url'    => 'comments/create.json',
                'method' => 'POST',
                'params' => ['comment', 'id', 'comment_ori', 'rip'],
                'maps'   => [
                    'commentOri' => 'comment_ori',
                    'remoteIp'   => 'rip',
                ],
            ],
            'reply' => [
                'url'    => 'comments/reply.json',
                'method' => 'POST',
                'params' => ['cid', 'id', 'comment', 'without_mention', 'comment_ori', 'rip'],
                'maps'   => [
                    'commentId'      => 'cid',
                    'withoutMention' => 'without_mention',
                    'commentOri'     => 'comment_ori',
                    'remoteIp'       => 'rip',
                ],
            ],
        ],

        'common' => [
            'codeToLocation' => [
                'url'         => 'common/code_to_location.json',
                'accessToken' => false,
                'params'      => ['codes'],
            ],
            'getCity' => [
                'url'         => 'common/get_city.json',
                'accessToken' => false,
                'params'      => ['provicne', 'capital', 'language'],
            ],
            'getProvince' => [
                'url'         => 'common/get_province.json',
                'accessToken' => false,
                'params'      => ['country', 'capital', 'language'],
            ],
            'getCountry' => [
                'url'         => 'common/get_country.json',
                'accessToken' => false,
                'params'      => ['capital', 'language'],
            ],
            'getTimezone' => [
                'url'         => 'common/get_timezone.json',
                'accessToken' => false,
                'params'      => ['language'],
            ],
        ],

        'account' => [
            'rateLimitStatus' => [
                'url'    => 'account/rate_limit_status.json',
                'params' => false,
            ],
            'getUid' => [
                'url'    => 'account/get_uid.json',
                'params' => false,
            ],
            'getEmail' => [
                'url'    => 'account/profile/email.json',
                'params' => false,
            ],
        ],
    ];

    protected $bizComponentList = [
        'status' => [
            'friendsTimeline' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/friends_timeline/biz.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user'],
                'maps'   => [
                    'sinceId'  => 'since_id',
                    'maxId'    => 'max_id',
                    'baseApp'  => 'base_app',
                    'trimUser' => 'trim_user',
                ],
            ],
            'userTimeline' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/user_timeline/biz.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'base_app', 'feature', 'trim_user', 'exclude_comment_like'],
                'maps'   => [
                    'sinceId'            => 'since_id',
                    'maxId'              => 'max_id',
                    'baseApp'            => 'base_app',
                    'trimUser'           => 'trim_user',
                    'excludeCommentLike' => 'exclude_comment_like',
                ],
            ],
            'repostTimeline' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/repost_timeline/biz.json',
                'params' => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                ],
            ],
            'mentions' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/mentions/biz.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source', 'filter_by_type'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                    'filterBySource' => 'filter_by_source',
                    'filterByType'   => 'filter_by_type',
                ],
            ],
            'repost' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/repost/biz.json',
                'params' => ['id', 'status', 'is_comment', 'rip'],
                'method' => 'POST',
                'maps'   => [
                    'isComment' => 'is_comment',
                    'remoteIp'  => 'rip',
                ],
            ],
            'destroy' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/destroy/biz.json',
                'params' => ['id'],
                'method' => 'POST',
            ],
            'update' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/update/biz.json',
                'params' => ['status', 'visible', 'list_id', 'lat', 'long', 'annotations', 'rip', 'is_longtext', 'custom_source'],
                'method' => 'POST',
                'maps'   => [
                    'listId'       => 'list_id',
                    'latitude'     => 'lat',
                    'longitude'    => 'long',
                    'remoteIp'     => 'rip',
                    'isLongtext'   => 'is_longtext',
                    'customSource' => 'custom_source',
                ],
            ],
            'upload' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/upload/biz.json',
                'params' => ['status', 'visible', 'list_id', 'pic', 'lat', 'long', 'annotations', 'rip'],
                'method' => 'POST',
                'maps'   => [
                    'listId'    => 'list_id',
                    'latitude'  => 'lat',
                    'longitude' => 'long',
                    'remoteIp'  => 'rip',
                ],
            ],
            'uploadUrlText' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/upload_url_text/biz.json',
                'params' => ['status', 'visible', 'list_id', 'url', 'pic_id', 'lat', 'long', 'annotations', 'rip', 'is_longtext'],
                'method' => 'POST',
                'maps'   => [
                    'listId'     => 'list_id',
                    'picId'      => 'pic_id',
                    'latitude'   => 'lat',
                    'longitude'  => 'long',
                    'remoteIp'   => 'rip',
                    'isLongtext' => 'is_longtext',
                ],
            ],
            'uploadPic' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/upload_pic/biz.json',
                'params' => ['pic'],
                'method' => 'POST',
            ],
            'queryId' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/queryid/biz.json',
                'params' => ['mid', 'type', 'is_batch', 'inbox', 'isBase62'],
                'maps'   => [
                    'isBatch' => 'is_batch',
                ],
            ],
            'queryMid' => [
                'url'    => 'https://c.api.weibo.com/2/statuses/querymid/biz.json',
                'params' => ['id', 'type', 'is_batch'],
                'maps'   => [
                    'isBatch' => 'is_batch',
                ],
            ],
        ],

        'comment' => [
            'show' => [
                'url'    => 'https://c.api.weibo.com/2/comments/show/biz.json',
                'params' => ['id', 'since_id', 'max_id', 'count', 'page', 'filter_by_author'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                ],
            ],
            'byMe' => [
                'url'    => 'https://c.api.weibo.com/2/comments/by_me/biz.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_source'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterBySource' => 'filter_by_source',
                ],
            ],
            'toMe' => [
                'url'    => 'https://c.api.weibo.com/2/comments/to_me/biz.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                    'filterBySource' => 'filter_by_source',
                ],
            ],
            'timeline' => [
                'url'    => 'https://c.api.weibo.com/2/comments/timeline/biz.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'trim_user'],
                'maps'   => [
                    'sinceId'  => 'since_id',
                    'maxId'    => 'max_id',
                    'trimUser' => 'trim_user',
                ],
            ],
            'mentions' => [
                'url'    => 'https://c.api.weibo.com/2/comments/mentions/biz.json',
                'params' => ['since_id', 'max_id', 'count', 'page', 'filter_by_author', 'filter_by_source'],
                'maps'   => [
                    'sinceId'        => 'since_id',
                    'maxId'          => 'max_id',
                    'filterByAuthor' => 'filter_by_author',
                    'filterBySource' => 'filter_by_source',
                ],
            ],
            'showBatch' => [
                'url'    => 'https://c.api.weibo.com/2/comments/show_batch/biz/new.json',
                'params' => ['cids'],
                'maps'   => ['commentIds' => 'cids'],
            ],
            'create' => [
                'url'    => 'https://c.api.weibo.com/2/comments/create/biz.json',
                'params' => ['comment', 'id', 'comment_ori', 'rip'],
                'method' => 'POST',
                'maps'   => [
                    'commentOri' => 'comment_ori',
                    'remoteIp'   => 'rip',
                ],
            ],
            'destroy' => [
                'url'    => 'https://c.api.weibo.com/2/comments/destroy/biz.json',
                'params' => ['cid'],
                'method' => 'POST',
                'maps'   => ['commentId' => 'cid'],
            ],
            'reply' => [
                'url'    => 'https://c.api.weibo.com/2/comments/reply/biz.json',
                'params' => ['cid', 'id', 'comment', 'without_mention', 'comment_ori', 'rip'],
                'method' => 'POST',
                'maps'   => [
                    'commentId'      => 'cid',
                    'withoutMention' => 'without_mention',
                    'commentOri'     => 'comment_ori',
                    'remoteIp'       => 'rip',
                ],
            ],
        ],

        'user' => [
            'show' => [
                'url'    => 'https://c.api.weibo.com/2/users/show/biz.json',
                'params' => false,
            ],
            'showBatch' => [
                'url'    => 'https://c.api.weibo.com/2/users/show_batch/other.json',
                'params' => ['uids', 'screen_name'],
                'maps'   => [
                    'ids'        => 'uids',
                    'userIds'    => 'uids',
                    'screenName' => 'screen_name',
                ],
            ],
            'countsBatch' => [
                'url'    => 'https://c.api.weibo.com/2/users/counts_batch/other.json',
                'params' => ['uids'],
                'maps'   => [
                    'ids'     => 'uids',
                    'userIds' => 'uids',
                ],
            ],
        ],

        'friendShip' => [
            'friends' => [
                'url'    => 'https://c.api.weibo.com/2/friendships/friends/biz.json',
                'params' => ['count', 'cursor', 'trim_status'],
                'maps'   => [
                    'trimStatus' => 'trim_status',
                ],
            ],
            'followers' => [
                'url'    => 'https://c.api.weibo.com/2/friendships/followers/biz.json',
                'params' => ['gender', 'province', 'city', 'age', 'type', 'bucket', 'max_time', 'cursor_uid'],
                'maps'   => [
                    'maxTime'   => 'max_time',
                    'cursorUid' => 'cursor_uid',
                ],
            ],
            'show' => [
                'url'    => 'https://c.api.weibo.com/2/friendships/show/biz.json',
                'params' => ['target_id', 'target_screen_name'],
                'maps'   => [
                    'targetId'         => 'target_id',
                    'targetScreenName' => 'target_screen_name',
                ],
            ],
        ],

        'url' => [
            'shorten' => [
                'url'         => 'https://c.api.weibo.com/2/short_url/shorten/biz.json',
                'accessToken' => false,
                'params'      => ['url_long'],
                'maps'        => ['urlLong' => 'url_long'],
            ],
            'expand' => [
                'url'         => 'https://c.api.weibo.com/2/short_url/expand/biz.json',
                'accessToken' => false,
                'params'      => ['url_short'],
                'maps'        => ['urlShort' => 'url_short'],
            ],
        ],
    ];

    protected $clientId;

    protected $clientSecret;

    protected $accessToken;

    public function __construct(array $options)
    {
        $this->setOptions($options);
        $this->initialize();
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

    protected function getComponentConfig(string $name)
    {
        if ('biz' === substr($name, 0, 3)) {
            $name = lcfirst(substr($name, 3));

            return $this->bizComponentList[$name] ?? null;
        }

        return $this->componentList[$name] ?? null;
    }

    protected function initialize(): void
    {
    }
}
