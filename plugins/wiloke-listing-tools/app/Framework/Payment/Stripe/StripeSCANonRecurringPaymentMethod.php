<?php

namespace WilokeListingTools\Framework\Payment\Stripe;

use WilokeListingTools\Controllers\Receipt\ReceiptInterface;
use WilokeListingTools\Framework\Helpers\FileSystem;
use WilokeListingTools\Framework\Helpers\General;
use WilokeListingTools\Framework\Payment\AddPaymentHookAction;
use WilokeListingTools\Framework\Payment\CreatedPaymentHook;
use WilokeListingTools\Framework\Payment\PaymentMethodInterface;
use WilokeListingTools\Framework\Payment\Receipt\ReceiptStructureInterface;
use WilokeListingTools\Framework\Payment\StripePayment;
use WilokeListingTools\Framework\Store\Session;
use WilokeListingTools\Frontend\User;

final class StripeSCANonRecurringPaymentMethod extends StripePayment implements PaymentMethodInterface
{
	public function getBillingType()
	{
		return wilokeListingToolsRepository()->get('payment:billingTypes', true)->sub('nonrecurring');
	}

	protected $aPaymentInfo;
	protected $oCharge;
	protected $relationshipID;
	protected $userID;
	protected $token;
	protected $postID;

	/**
	 * @return array
	 */
	private function createSession()
	{
		$aLineItems = [
			'name'        => $this->oReceipt->getPlanName(),
			'description' => $this->oReceipt->getPlanDescription(),
			'amount'      => $this->oApiContext->zeroDecimal * $this->oReceipt->getTotal(),
			'currency'    => $this->oReceipt->getCurrency(),
			'quantity'    => 1
		];

		$featuredImg = $this->oReceipt->getPlanFeaturedImg();

		if (!empty($featuredImg)) {
			$aLineItems['images'] = [$featuredImg];
		}

		try {
			$postID = Session::getPaymentObjectID(false);
			$oSession = \Stripe\Checkout\Session::create([
				'payment_method_types' => ['card'],
				'line_items'           => [
					$aLineItems
				],
				'success_url'          => $this->oReceipt->getThankyouURL([
					'postID'      => $postID,
					'category'    => Session::getPaymentCategory(false),
					'promotionID' => Session::getSession('promotionID', true)
				]),
				'metadata'             => [
					'userID'    => $this->oReceipt->getUserID(),
				],
				'cancel_url'           => $this->oReceipt->getCancelUrl()
			]);

			FileSystem::logSuccess('AddListing: ' . General::getDebugAddListingStep('Created Strip Session'));
			$this->token = $oSession->payment_intent;
			$this->postID = $postID;

			$oAddPaymentHook = new CreatedPaymentHook(new StripeNonRecurringCreatedPaymentHook($this));
			$oAddPaymentHook->doSuccess();

			return [
				'status'    => 'success',
				'sessionID' => $oSession->id,
				'gateway'   => $this->gateway
			];
		}
		catch (\Exception $oException) {
			return [
				'status' => 'error',
				'msg'    => $oException->getMessage()
			];
		}
	}

	/**
	 * @param ReceiptStructureInterface $oReceipt
	 *
	 * @return array
	 */
	public function proceedPayment(ReceiptStructureInterface $oReceipt)
	{
		$this->oReceipt = $oReceipt;
		$this->setup();

		try {
			return $this->createSession();
		}
		catch (\Exception $oE) {
			return [
				'status' => 'error',
				'msg'    => $oE->getMessage()
			];
		}
	}

	public function __get($name)
	{
		if (property_exists($this, $name)) {
			return $this->$name;
		} else {
			FileSystem::logError('Stripe: The property ' . $name . ' does not exist');

			return false;
		}
	}
}
