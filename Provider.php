<?php

namespace SocialiteProviders\FreshBooks;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'FRESHBOOKS';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [ '' ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl ($state)
    {
        return $this->buildAuthUrlFromBase('https://my.freshbooks.com/service/auth/oauth/authorize?client_id=323e2cce713d354fbf7497562141e07afbd123e03c211c0ec266062cc9e00752&amp;response_type=code&amp;redirect_uri=https://63821e8c.ngrok.io', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl ()
    {
        return 'https://api.freshbooks.com/auth/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken ($token)
    {
        $response = $this->getHttpClient()->get('https://api.freshbooks.com/auth/api/v1/users/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject (array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => array_get($user, 'response.id'),
            'nickname' => array_get($user, 'response.email'),
            'name'     => array_get($user, 'response.first_name') . ' ' . array_get($user, 'response.last_name'),
            'email'    => array_get($user, 'response.email'),
            //'avatar'   => $user['avatar'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields ($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}
