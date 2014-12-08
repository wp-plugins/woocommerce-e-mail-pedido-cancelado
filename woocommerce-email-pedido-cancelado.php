<?php
/**
 * Plugin Name: Woocommerce E-mail Pedido Cancelado
 * Plugin URI: http://www.agenciamagma.com.br
 * Description: Send email to user when his order is cancelled.
 * Version: 1.0.0
 * Author: agenciamagma
 * Author URI: http://agenciamagma.com.br
 * License: GPL2
 */

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('AG_Magma_Email_Pedido_Cancelado')):

class AG_Magma_Email_Pedido_Cancelado {

	const VERSION = '1.0.0';

	private static $instance = null;

	public static function get_instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action('woocommerce_order_status_cancelled', array($this, 'send_mail'));
	}

	public function send_mail($order_id) {
		$order = new WC_Order($order_id);
		$post_meta = get_post_meta($order_id);
		$user_email = $post_meta['_billing_email'][0];
		$user_first_name = $post_meta['_billing_first_name'][0];
		
		$msg =  'Olá, ' . $user_first_name . '.<br />';
		$msg .= 'Informamos que o seu pedido (<a href="' . $order->get_view_order_url() . '">#' . $order_id . '</a>) foi cancelado.<br />';
		$msg .= 'Obrigado.';
		wc_mail($user_email, get_bloginfo('name') . ' - Pedido Cancelado', $msg);
	}
}

/**
 * Initialize the plugin.
 */
add_action('woocommerce_init', array('AG_Magma_Email_Pedido_Cancelado', 'get_instance'), 0);

endif;
