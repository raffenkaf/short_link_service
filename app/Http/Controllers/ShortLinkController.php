<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Services\ShortLinkCacheService;
use Illuminate\Http\Request;
use RedisException;


class ShortLinkController extends Controller
{
    public function __construct(protected ShortLinkCacheService $shortLinkCacheService)
    {
    }

    public function index()
    {
        $shortLinks = ShortLink::orderBy('id', 'desc')
            ->limit(4)
            ->get();

        return view('index', ['shortLinks' => $shortLinks]);
    }

    /**
     * @throws RedisException
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => ['required', 'url'],
            'redirect_limit' => ['required', 'integer', 'min:0'],
            'expires_at' => ['required', 'integer', 'min:1', 'max:86400']
        ]);

        $shortLink = new ShortLink([
            'url' => $request->get('url'),
            'redirect_limit' => $request->get('redirect_limit'),
            'expires_at' => $request->get('expires_at'),
        ]);
        $shortLink->save();

        $this->shortLinkCacheService->addLinkCodeToCache($shortLink);
        $this->shortLinkCacheService->addRedirectLimitToCache($shortLink);

        return redirect('/');
    }

    /**
     * @throws RedisException
     */
    public function redirect(string $code)
    {
        $url = $this->shortLinkCacheService->getUrlByCodeFromCache($code);
        $this->shortLinkCacheService->decreaseRedirectLimitInCache($code);
        $this->shortLinkCacheService->checkAndHandleRedirectLimitInCache($code);

        if ($url !== '') {
            return redirect($url);
        }

        abort(404);
    }
}
