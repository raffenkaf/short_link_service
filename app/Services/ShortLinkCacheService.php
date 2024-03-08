<?php

namespace App\Services;

use App\Models\ShortLink;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use RedisException;

class ShortLinkCacheService
{
    private string $defaultPrefix = 'short_link:';
    private string $redirectLimitSuffix = ':redirect_limit';

    /**
     * @throws RedisException
     */
    public function addLinkCodeToCache(ShortLink $shortLink): void
    {
        $expiredIsSeconds = $shortLink->expires_at->diffInSeconds(new Carbon());

        Redis::set($this->getHashKey($shortLink->link_code), $shortLink->url, 'EX', $expiredIsSeconds);
    }

    public function addRedirectLimitToCache(ShortLink $shortLink): void
    {
        $expiredIsSeconds = $shortLink->expires_at->diffInSeconds(new Carbon());

        if ($shortLink->redirect_limit > 0) {
            Redis::set(
                $this->getRedirectLimitHashKey($shortLink->link_code),
                $shortLink->redirect_limit,
                'EX',
                $expiredIsSeconds
            );
        }
    }

    /**
     * @throws RedisException
     */
    public function getUrlByCodeFromCache(string $code): string
    {
        return (string)Redis::get($this->getHashKey($code));
    }

    public function decreaseRedirectLimitInCache(string $code): void
    {
        $redirectLimit = Redis::get($this->getRedirectLimitHashKey($code));
        if (!$redirectLimit) {
            return;
        }

        Redis::decr($this->getRedirectLimitHashKey($code));
    }

    private function getHashKey(string $code): string
    {
        return $this->defaultPrefix . $code;
    }

    private function getRedirectLimitHashKey(string $code): string
    {
        return $this->getHashKey($code) . $this->redirectLimitSuffix;
    }

    public function checkAndHandleRedirectLimitInCache(string $code): void
    {
        $redirectLimit = Redis::get($this->getRedirectLimitHashKey($code));

        if (!is_numeric($redirectLimit)) {
            return;
        }

        if ((int)$redirectLimit < 1) {
            Redis::del($this->getHashKey($code));
            Redis::del($this->getRedirectLimitHashKey($code));
        }
    }
}
