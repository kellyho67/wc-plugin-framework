<?php
/**
 * WooCommerce Payment Gateway Framework
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the plugin to newer
 * versions in the future. If you wish to customize the plugin for your
 * needs please refer to http://www.skyverge.com
 *
 * @package   SkyVerge/WooCommerce/Payment-Gateway/Classes
 * @author    SkyVerge
 * @copyright Copyright (c) 2013, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SV_WC_Payment_Gateway_Payment_Token' ) ) :

/**
 * WooCommerce Payment Gateway Token
 *
 * Represents a credit card or check payment token
 *
 * @version 0.1
 */
class SV_WC_Payment_Gateway_Payment_Token {


	/**
	 * @var string payment gateway token
	 */
	private $token;

	/**
	 * @var array associated token data
	 */
	 protected $data;


	/**
	 * Initialize a payment token with associated $data which is expected to
	 * have the following members:
	 *
	 * default   - boolean optional indicates this is the default payment token
	 * type      - string credit card type (visa, mc, amex, disc, diners, jcb) or echeck
	 * last_four - string last four digits of account number
	 * exp_month - string optional expiration month (credit card only)
	 * exp_year  - string optional expiration year (credit card only)
	 *
	 * @since 0.1
	 * @param string $token the payment gateway token
	 * @param array $data associated data
	 */
	public function __construct( $token, $data ) {

		$this->token = $token;
		$this->data  = $data;

	}


	/**
	 * Returns the payment token string
	 *
	 * @since 0.1
	 * @return string payment token string
	 */
	public function get_token() {

		return $this->token;

	}


	/**
	 * Returns true if this payment token is default
	 *
	 * @since 0.1
	 * @return boolean true if this payment token is default
	 */
	public function is_default() {

		return isset( $this->data['default'] ) && $this->data['default'];

	}


	/**
	 * Makes this payment token the default or a non-default one
	 *
	 * @since 0.1
	 * @param boolean $default true or false
	 */
	public function set_default( $default ) {

		$this->data['default'] = $default;

	}


	/**
	 * Returns true if this payment token represents a credit card
	 *
	 * @since 0.1
	 * @return boolean true if this payment token represents a credit card
	 */
	public function is_credit_card() {

		return 'echeck' != $this->data['type'];

	}


	/**
	 * Returns true if this payment token represents a check
	 *
	 * @since 0.1
	 * @return boolean true if this payment token represents a check
	 */
	public function is_check() {

		return ! $this->is_credit_card();

	}


	/**
	 * Returns the payment type (visa, mc, amex, disc, diners, jcb, echeck, etc)
	 *
	 * @since 0.1
	 * @return string the payment type
	 */
	public function get_type() {

		return $this->data['type'];

	}


	/**
	 * Returns the full payment type (Visa, MasterCard, American Express,
	 * Discover, Diners, JCB, eCheck, etc)
	 *
	 * @since 0.1
	 * @return string the payment type
	 */
	public function get_type_full() {

		return self::type_to_name( $this->get_type() );

	}


	/**
	 * Translates a card type to a full name, ie 'mc' => 'MasterCard'
	 *
	 * @since 0.1
	 * @param string $type the card type, ie 'mc', 'amex', etc
	 * @return string the card name, ie 'MasterCard', 'American Express', etc
	 */
	public static function type_to_name( $type ) {

		$name = '';

		// special cases
		switch ( $type ) {

			case 'mc':         $name = 'MasterCard';       break;
			case 'amex':       $name = 'American Express'; break;
			case 'disc':       $name = 'Discover';         break;
			case 'jcb':        $name = 'JCB';              break;
			case 'cartebleue': $name = 'CarteBleue';       break;
			case 'paypal':     $name = 'PayPal';           break;
			case 'echeck':     $name = 'eCheck';           break;

		}

		// default: replace dashes with spaces and uppercase all words
		if ( ! $name ) $name = ucwords( str_replace( '-', ' ', $type ) );

		return apply_filters( 'wc_payment_gateway_payment_type_to_name', $name, $type );

	}


	/**
	 * Returns the last four digits of the credit card or check account number
	 *
	 * @since 0.1
	 * @return string last four of account
	 */
	public function get_last_four() {

		return $this->data['last_four'];

	}


	/**
	 * Returns the expiration month of the credit card.  This should only be
	 * called for credit card tokens
	 *
	 * @since 0.1
	 * @return string expiration month as a two-digit number
	 */
	public function get_exp_month() {

		return $this->data['exp_month'];

	}


	/**
	 * Returns the expiration year of the credit card.  This should only be
	 * called for credit card tokens
	 *
	 * @since 0.1
	 * @return string expiration year as a four-digit number
	 */
	public function get_exp_year() {

		return $this->data['exp_year'];

	}


	/**
	 * Returns a representation of this token suitable for persisting to a
	 * datastore
	 *
	 * @since 0.1
	 * @return mixed datastore representation of token
	 */
	public function to_datastore_format() {

		return $this->data;

	}


}

endif;  // class exists check