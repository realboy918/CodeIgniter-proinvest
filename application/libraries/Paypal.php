<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paypal
{
	private $clientID="";
	private $clientSecret="";
	protected $redirectUrl="";
	protected $cancelUrl="";
	protected $paymentMethod = "paypal";
	protected $currency = "USD";
	protected $invoiceNumber = "";
	protected $mode="";       
	//intention of the payment
	protected $intent = "sale";

	public function __construct(array $config = array())
	{
		require_once APPPATH.'third_party/paypal/autoload.php';
		$this->initialize($config);
		$this->_safe_mode = ( ! is_php('5.4') && ini_get('safe_mode'));
		log_message('info', 'Paypal Class Initialized');
	}
	/**
	 * Initialize preferences
	 *
	 * @param	array	$config
	 * @return	this
	 */
	public function initialize(array $config = array()){
		if ($config) {
			$this->clear();
			foreach ($config as $key => $val)
			{
				if (isset($this->$key))
				{
					$method = 'set_'.$key;

					if (method_exists($this, $method))
					{
						$this->$method($val);
					}
					else
					{
						$this->$key = $val;
					}
				}
			}
		}
		return $this;
	}
	public function clear()
	{
		$this->clientSecret		= '';
		$this->clientID			= '';
		$this->redirectUrl		= '';
		$this->cancelUrl		= '';
		$this->invoiceNumber    = '';

		return $this;
	}
	/**
	 * Set ID
	 *
	 * @param	string
	 * @return	PAYPAL
	 */
	public function set_clientID($value)
	{
		if ($value && $value !=$this->clientID) {
			$this->clientID = $value;
		}
		return $this;
	}
	/**
	 * Set clientSecret
	 *
	 * @param	string
	 * @return	PAYPAL
	 */
	public function set_clientSecret($value)
	{
		$this->clientSecret = $value;
		return $this;
	}
	public function set_invoiceNumber($value)
	{
		$this->invoiceNumber = $value;
		return $this;
	}
	public function set_redirectUrl($value)
	{
		$this->redirectUrl = $value;
		return $this;
	}
	public function set_cancelUrl($value)
	{
		$this->cancelUrl = $value;
		return $this;
	}
	/**
	 * pay
	 *
	 * @param	string amount to pay
	 * @return	array payment details and approval link
	 */
	public function pay($payment)
	{
		$result = array("error"=>"","payment"=>"","approval_url"=>"");
		$apiContext = new \PayPal\Rest\ApiContext(
			new \PayPal\Auth\OAuthTokenCredential(
				$this->clientID,
				$this->clientSecret
			)
		);
		$apiContext->setConfig([
			'mode' => $this->mode,
		]);
		// After Step 2
		$payer = new \PayPal\Api\Payer();
		$payer->setPaymentMethod($this->paymentMethod);

		$amount = new \PayPal\Api\Amount();
		$amount->setTotal($payment);
		$amount->setCurrency($this->currency);

		$transaction = new \PayPal\Api\Transaction();
		$transaction->setAmount($amount);
		$transaction->setInvoiceNumber($this->invoiceNumber);

		$redirectUrls = new \PayPal\Api\RedirectUrls();
		$redirectUrls->setReturnUrl($this->redirectUrl)
		->setCancelUrl($this->cancelUrl);

		$payment = new \PayPal\Api\Payment();
		$payment->setIntent($this->intent)
		->setPayer($payer)
		->setTransactions(array($transaction))
		->setRedirectUrls($redirectUrls);

		// After Step 3
		try {
			$payment->create($apiContext);
			$result["payment"] = $payment;
			$result["approval_url"] = $payment->getApprovalLink();
			//echo $payment;
			/*
			$payment->create($apiContext);
			echo $payment;
    		echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n"; */
		}
		catch (\PayPal\Exception\PayPalConnectionException $ex) {
			$result["error"] = $ex->getData();
			echo $ex->getData();
		}
		return $result;
	}

}

/* End of file Paypal.php */
/* Location: ./application/libraries/Paypal.php */