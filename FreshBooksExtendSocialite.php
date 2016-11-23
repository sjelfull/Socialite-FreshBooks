<?php

namespace SocialiteProviders\FreshBooks;

use SocialiteProviders\Manager\SocialiteWasCalled;

class FreshBooksExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle (SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('freshbooks', __NAMESPACE__ . '\Provider');
    }
}
