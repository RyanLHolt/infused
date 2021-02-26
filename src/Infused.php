<?php

namespace RyanLHolt\Infused;

use Illuminate\Support\Facades\Log;
use Infusionsoft\InfusionsoftException;
use RyanLHolt\Infused\Models\InfusionsoftToken;

class Infused
{
    protected $app;
    protected $infusionsoft;

    public function __construct($app)
    {
        $this->app = $app;

        $this->infusionsoft = $this->app->make('infusionsoft');
    }

    public function updateToken($token)
    {
        $tokenModel = InfusionsoftToken::firstOrNew([
            'user_id' => auth()->id(),
        ]);

        $tokenExtraInfo = $token->getExtraInfo();

        $tokenAppId = explode('|', $tokenExtraInfo['scope']);
        $tokenAppId = $tokenAppId[1];

        $tokenModel->user_id = auth()->id();
        $tokenModel->infusionsoft_app_id = $tokenAppId;
        $tokenModel->access_token = $token->getAccessToken();
        $tokenModel->refresh_token = $token->getRefreshToken();
        $tokenModel->token_type = $tokenExtraInfo['token_type'];
        $tokenModel->end_of_life = $token->getEndOfLife();
        $tokenModel->serialized_token = serialize($token);

        $tokenModel->save();
    }

    public function getAccessToken($code = null): bool
    {
        try {
            $token = (isset($code))
                ? $this->infusionsoft->requestAccessToken($code)
                : $this->infusionsoft->refreshAccessToken();

            $this->updateToken($token);

            return true;
        } catch (InfusionsoftException $e) {
            Log::error('Error refreshing token: '.$e->getMessage());

            return false;
        }
    }
}
