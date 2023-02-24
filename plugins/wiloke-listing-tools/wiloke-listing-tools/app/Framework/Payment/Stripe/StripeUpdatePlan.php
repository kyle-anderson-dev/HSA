<?php

namespace WilokeListingTools\Framework\Payment\Stripe;

use WilokeListingTools\Controllers\Retrieve\NormalRetrieve;
use WilokeListingTools\Controllers\RetrieveController;
use WilokeListingTools\Framework\Payment\StripePayment;

final class StripeUpdatePlan extends StripePayment
{
    /**
     * @var String $planID This is post type slug
     */
    private $planID;
    private $oPlan;
    private $aArgs;
    private $oRetrieve;
    
    public function __construct($planID)
    {
        $this->planID = $planID;
        
        $this->oRetrieve = new RetrieveController(new NormalRetrieve());
    }
    
    public function setUpdateTrialDays($days)
    {
        if (empty($days)) {
            $this->aArgs['trial_period_days'] = null;
        } else {
            $this->aArgs['trial_period_days'] = abs($days);
        }
        return $this;
    }
    
    public function getPlan()
    {
        $aStatus = $this->setApiContext();
        if ($aStatus['status'] == 'error') {
            return $this->oRetrieve->error([
                'msg' => 'This plan does not exist'
            ]);
        }
        
        try {
            $this->oPlan = \Stripe\Plan::retrieve($this->planID);
            return $this->oRetrieve->success([]);
        } catch (\Exception $oException) {
            return $this->oRetrieve->error([
                'msg' => $oException->getMessage()
            ]);
        }
    }
    
    public function hasPlan()
    {
        $aStatus = $this->getPlan();
        return ($aStatus['status'] == 'success');
    }
    
    public function getArgs()
    {
        return $this->aArgs;
    }
    
    public function updatePlan()
    {
        try {
            \Stripe\Plan::update(
                $this->planID,
                $this->aArgs
            );
            
            return $this->oRetrieve->success([
                'msg' => sprintf('The plan %s has been updated', $this->planID)
            ]);
            
        } catch (\Exception $oException) {
            return $this->oRetrieve->error([
                'msg' => $oException->getMessage()
            ]);
        }
    }
}
