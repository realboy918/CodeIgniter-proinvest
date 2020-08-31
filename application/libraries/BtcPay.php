<?php
/**
 * Payeer Payment Library
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is available through the world-wide-web at this URL:
 * https://choosealicense.com/licenses/gpl-3.0/
 *
 * @category        BTCPay
 * @package         codeigniter/libraries
 * @version         1.0
 * @author          Axis96 <axis96.co>
 * @copyright       Copyright (c) 2020 Axis96
 * @license         https://choosealicense.com/licenses/gpl-3.0/
 *
 * EXTENSION INFORMATION
 *
 * PAYEER       https://btcpayserver.org
 *
 */
class BtcPay
{
    private $store_id = '';                                      // Store ID
    private $order_id = '';                                   // Order ID
    private $amount = '';                                    // deposit amount
    private $currency = 'USD';                                    // currency
    private $ipn = '';
    private $redirect = '';
    private $url = '';
    /**
     * Constructor.
     *
     * @param string $config
     *
     */
    public function __construct($config = false)
    {
        // Присваеваем приватным переменным переданные в конструктор настройки
        if (isset($config['store_id'])) $this->store_id = $config['store_id'];
        if (isset($config['order_id'])) $this->order_id = $config['order_id'];
        if (isset($config['amount'])) $this->amount = $config['amount'];
        if (isset($config['currency'])) $this->currency = $config['currency'];
        if (isset($config['ipn'])) $this->ipn = $config['ipn'];
        if (isset($config['redirect'])) $this->redirect = $config['redirect'];
        if (isset($config['url'])) $this->url = $config['url'];
    }

    /**
     * Form submit and redirect
     *
     *
     * @return string
     */

    public function submitForm()
    {
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <script type="text/javascript">
                function closethisasap() {
                    document.forms["redirectpost"].submit();
                }
            </script>
        </head>
        <body onload="closethisasap();">
        <style type="text/css"> .btcpay-form { display: inline-flex; align-items: center; justify-content: center; } .btcpay-form--inline { flex-direction: row; } .btcpay-form--block { flex-direction: column; } .btcpay-form--inline .submit { margin-left: 15px; } .btcpay-form--block select { margin-bottom: 10px; } .btcpay-form .btcpay-custom-container{ text-align: center; }.btcpay-custom { display: flex; align-items: center; justify-content: center; } .btcpay-form .plus-minus { cursor:pointer; font-size:25px; line-height: 25px; background: #DFE0E1; height: 30px; width: 45px; border:none; border-radius: 60px; margin: auto 5px; display: inline-flex; justify-content: center; } .btcpay-form select { -moz-appearance: none; -webkit-appearance: none; appearance: none; color: currentColor; background: transparent; border:1px solid transparent; display: block; padding: 1px; margin-left: auto; margin-right: auto; font-size: 11px; cursor: pointer; } .btcpay-form select:hover { border-color: #ccc; } #btcpay-input-price { -moz-appearance: none; -webkit-appearance: none; border: none; box-shadow: none; text-align: center; font-size: 25px; margin: auto; border-radius: 5px; line-height: 35px; background: #fff; } </style>
            <form name="redirectpost" method="POST" action="<?php echo $this->url; ?>/api/v1/invoices" class="btcpay-form btcpay-form--block">
                <?php
                echo '<input type="hidden" name="storeId" value="'. $this->store_id . '" /> ';
                echo '<input type="hidden" name="serverIpn" value="'. $this->ipn .'" />';
                echo '<input type="hidden" name="browserRedirect" value="'. $this->redirect .'" />';
                echo '<input type="hidden" name="orderId" value="'. $this->order_id . '" /> ';
                echo '<input type="hidden" name="price" value="'. $this->amount .'" /> ';
                echo '<input type="hidden" name="currency" value="'. $this->currency .'" /> ';
                ?>
            </form>
        </body>
        </html>
        <?php
        exit;
    }

}