<?php

namespace WilokeListingTools\Framework\Payment;

use WilokeListingTools\Controllers\Retrieve\NormalRetrieve;
use WilokeListingTools\Controllers\RetrieveController;
use WilokeListingTools\Framework\Helpers\GetWilokeSubmission;
use WilokeListingTools\Framework\Helpers\Message;
use WilokeListingTools\Framework\Payment\Receipt\ReceiptStructureInterface;
use WilokeListingTools\Models\UserModel;

abstract class StripePayment
{
    /**
     * @var ReceiptStructureInterface
     */
    protected $oReceipt;
    protected $gateway = 'stripe';
    protected $aConfiguration;
    protected $oApiContext;
    protected $customerID;
    
    protected function setApiContext()
    {
        $oRetrieve = new RetrieveController(new NormalRetrieve());
        
        $this->aConfiguration = GetWilokeSubmission::getAll();
        $msg                  = esc_html__('The Stripe has not configured yet!', 'wiloke-listing-tools');
        
        if (!GetWilokeSubmission::isGatewaySupported($this->gateway)) {
            return $oRetrieve->error(
                [
                    'msg' => $msg
                ]
            );
        }
        
        $this->oApiContext['secretKey']   = $this->aConfiguration['stripe_secret_key'];
        $this->oApiContext['zeroDecimal'] = $this->getZeroDecimal();
        settype($this->oApiContext, 'object');
        
        \Stripe\Stripe::setApiKey($this->oApiContext->secretKey);
        $this->getCustomerID();
    
        return $oRetrieve->success([]);
    }
    
    /**
     * If user has already executed a session before, We will have his/her customer id
     *
     * @return void
     */
    protected function getCustomerID()
    {
        $this->customerID = UserModel::getStripeID();
    }
    
    protected function isLiveMode()
    {
        return $this->aConfiguration['mode'] == 'live';
    }
    
    protected function getEndpointSecret()
    {
        if (!isset($this->aConfiguration['stripe_endpoint_secret'])) {
            return '';
        }
        
        return trim($this->aConfiguration['stripe_endpoint_secret']);
    }
    
    public function getConfiguration($field)
    {
        return $this->aConfiguration[$field];
    }
    
    public function getZeroDecimal()
    {
        return !isset($this->aConfiguration['stripe_zero_decimal']) ||
               empty($this->aConfiguration['stripe_zero_decimal']) ? 1 :
            absint($this->aConfiguration['stripe_zero_decimal']);
    }
    
    protected function setup()
    {
        $this->userID = $this->oReceipt->getUserID();
        $this->setApiContext();
    }
}
