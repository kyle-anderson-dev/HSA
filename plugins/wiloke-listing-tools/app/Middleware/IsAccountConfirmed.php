<?php

namespace WilokeListingTools\Middleware;

use WilokeListingTools\Framework\Helpers\GetSettings;
use WilokeListingTools\Framework\Routing\InterfaceMiddleware;
use WilokeListingTools\Frontend\User;

class IsAccountConfirmed implements InterfaceMiddleware
{
    public $msg = '';

    public function handle(array $aOptions)
    {
        if (!isset($aOptions['userID']) || empty($aOptions['userID'])) {
            $aOptions['userID'] = User::getCurrentUserID();
        }

        if (\WilokeThemeOptions::getOptionDetail('toggle_confirmation') !== 'enable') {
            return true;
        }

        if (current_user_can('administrator')) {
            return true;
        }

        if (!User::isAccountConfirmed($aOptions['userID'])) {
            $this->msg =
                esc_html__('We have sent an email with a confirmation link to your email address. In order to complete the sign-up process, please click the confirmation link.',
                    'wiloke-listing-tools');

            return false;
        }

        return true;
    }
}
