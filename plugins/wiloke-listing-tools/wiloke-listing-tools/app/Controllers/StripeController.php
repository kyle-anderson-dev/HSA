<?php

namespace WilokeListingTools\Controllers;

use WilokeListingTools\Framework\Helpers\General;
use WilokeListingTools\Framework\Helpers\GetSettings;
use WilokeListingTools\Framework\Helpers\GetWilokeSubmission;
use WilokeListingTools\Framework\Payment\Stripe\StripeUpdatePlan;
use WilokeListingTools\Framework\Routing\Controller;
use WilokeListingTools\Models\PaymentMetaModel;
use WilokeListingTools\Models\PaymentModel;

class StripeController extends Controller
{
    public function __construct()
    {
        $aBillingTypes = wilokeListingToolsRepository()->get('payment:billingTypes', false);
        foreach ($aBillingTypes as $billingType) {
            add_action('wilcity/wiloke-listing-tools/' . $billingType . '/payment-completed', [$this, 'setStripeChargeID']);
        }

        /**
         * When purchase first listing plan via Stripe, a Plan will be created on Stripe. This plan will save
         * Period Day and Trial day, Admin may change it under Website -> Listing Plans -> We will update it if
         * there is a changed
         *
         */
        add_action('update_postmeta', [$this, 'updateStripePlan'], 10, 4);
    }

    public function updateStripePlan($metaID, $postID, $metaKey, $metaValue)
    {
        if (!General::isAdmin() || !current_user_can('administrator')) {
            return false;
        }

        if (get_post_type($postID) !== 'listing_plan') {
            return false;
        }

        $oStripeUpdate = new StripeUpdatePlan(get_post_field('post_name', $postID));
        $aNewSettings = isset($_POST['wiloke_custom_field']) && is_array($_POST['wiloke_custom_field']) ?
            $_POST['wiloke_custom_field'] : [];

        $aCurrentSettings = GetSettings::getPlanSettings($postID);

        if ($oStripeUpdate->hasPlan()) {
            $aArgs = ['regular_price', 'trial_period', 'regular_period'];
            foreach ($aArgs as $args) {
                if (empty($aCurrentSettings) ||
                    (
                        isset($aNewSettings['add_listing_plan'][$args]) &&
                        $aNewSettings['add_listing_plan'][$args] != $aCurrentSettings[$args]
                    )
                ) {
                    switch ($args) {
                        case 'trial_period':
                            $oStripeUpdate->setUpdateTrialDays(sanitize_text_field($aNewSettings['add_listing_plan'][$args]));
                            break;
                    }
                }
            }

            if ($oStripeUpdate->getArgs()) {
                $aStatus = $oStripeUpdate->updatePlan();
            }
        }
    }

    /**
     * This setting is needed for Refund feature
     */
    public function setStripeChargeID($aInfo)
    {
        if (!GetWilokeSubmission::isNonRecurringPayment($aInfo['billingType'])) {
            return false;
        }

        $gateway = PaymentModel::getField('gateway', $aInfo['paymentID']);

        if ($gateway !== 'stripe') {
            return false;
        }

        $aPaymentMetaInfo = PaymentMetaModel::getPaymentInfo($aInfo['paymentID']);

        if (isset($aPaymentMetaInfo['chargeID'])) {
            PaymentMetaModel::setStripeChargeID($aInfo['paymentID'], $aPaymentMetaInfo['chargeID']);
        }
    }
}
