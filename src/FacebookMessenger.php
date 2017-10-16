<?php

namespace Shield\FacebookMessenger;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shield\Shield\Contracts\Service;

/**
 * Class FacebookMessenger
 *
 * @package \Shield\FacebookMessenger
 */
class FacebookMessenger implements Service
{
    public function verify(Request $request, Collection $config): bool
    {
        $generated = 'sha1=' . hash_hmac('sha1', $request->getContent(), $config->get('app_secret'));

        return hash_equals($generated, $request->header('X-Hub-Signature'));
    }

    public function headers(): array
    {
        return ['X-Hub-Signature'];
    }
}
