<?php
/*
Plugin Name: BitTube Woocommerce Gateway
Plugin URI:
Description: Extends WooCommerce by adding a BitTube Gateway
Version: 1.0.0
Tested up to: 4.9.8
Forked from Monero Integrations Version Author: mosu-forge, SerHack, Bittube
*/
// This code isn't for Dark Net Markets, please report them to Authority!

defined( 'ABSPATH' ) || exit;

// Constants, you can edit these if you fork this repo
define('BITTUBE_GATEWAY_MAINNET_EXPLORER_URL', 'https://explorer.bit.tube');
define('BITTUBE_GATEWAY_TESTNET_EXPLORER_URL', 'http://testnet-explorer.bit.tube');
define('BITTUBE_GATEWAY_ADDRESS_PREFIX', 0x12);
define('BITTUBE_GATEWAY_ADDRESS_PREFIX_INTEGRATED', 0x13);
define('BITTUBE_GATEWAY_ATOMIC_UNITS', 8);
define('BITTUBE_GATEWAY_ATOMIC_UNIT_THRESHOLD', 10); // Amount under in atomic units payment is valid
define('BITTUBE_GATEWAY_DIFFICULTY_TARGET', 120);

// Do not edit these constants
define('BITTUBE_GATEWAY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BITTUBE_GATEWAY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BITTUBE_GATEWAY_ATOMIC_UNITS_POW', pow(10, BITTUBE_GATEWAY_ATOMIC_UNITS));
define('BITTUBE_GATEWAY_ATOMIC_UNITS_SPRINTF', '%.'.BITTUBE_GATEWAY_ATOMIC_UNITS.'f');

// Include our Gateway Class and register Payment Gateway with WooCommerce
add_action('plugins_loaded', 'bittube_init', 1);
function bittube_init() {

    // If the class doesn't exist (== WooCommerce isn't installed), return NULL
    if (!class_exists('WC_Payment_Gateway')) return;

    // If we made it this far, then include our Gateway Class
    require_once('include/class-bittube-gateway.php');

    // Create a new instance of the gateway so we have static variables set up
    new BitTube_Gateway($add_action=false);

    // Include our Admin interface class
    require_once('include/admin/class-bittube-admin-interface.php');

    add_filter('woocommerce_payment_gateways', 'bittube_gateway');
    function bittube_gateway($methods) {
        $methods[] = 'BitTube_Gateway';
        return $methods;
    }

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'bittube_payment');
    function bittube_payment($links) {
        $plugin_links = array(
            '<a href="'.admin_url('admin.php?page=bittube_gateway_settings').'">'.__('Settings', 'bittube_gateway').'</a>'
        );
        return array_merge($plugin_links, $links);
    }

    add_filter('cron_schedules', 'bittube_cron_add_one_minute');
    function bittube_cron_add_one_minute($schedules) {
        $schedules['one_minute'] = array(
            'interval' => 60,
            'display' => __('Once every minute', 'bittube_gateway')
        );
        return $schedules;
    }

    add_action('wp', 'bittube_activate_cron');
    function bittube_activate_cron() {
        if(!wp_next_scheduled('bittube_update_event')) {
            wp_schedule_event(time(), 'one_minute', 'bittube_update_event');
        }
    }

    add_action('bittube_update_event', 'bittube_update_event');
    function bittube_update_event() {
        BitTube_Gateway::do_update_event();
    }

    add_action('woocommerce_thankyou_'.BitTube_Gateway::get_id(), 'bittube_order_confirm_page');
    add_action('woocommerce_order_details_after_order_table', 'bittube_order_page');
    add_action('woocommerce_email_after_order_table', 'bittube_order_email');

    function bittube_order_confirm_page($order_id) {
        BitTube_Gateway::customer_order_page($order_id);
    }
    function bittube_order_page($order) {
        if(!is_wc_endpoint_url('order-received'))
            BitTube_Gateway::customer_order_page($order);
    }
    function bittube_order_email($order) {
        BitTube_Gateway::customer_order_email($order);
    }

    add_action('wc_ajax_bittube_gateway_payment_details', 'bittube_get_payment_details_ajax');
    function bittube_get_payment_details_ajax() {
        BitTube_Gateway::get_payment_details_ajax();
    }

    add_filter('woocommerce_currencies', 'bittube_add_currency');
    function bittube_add_currency($currencies) {
        $currencies['BitTube'] = __('BitTube', 'bittube_gateway');
        return $currencies;
    }

    add_filter('woocommerce_currency_symbol', 'bittube_add_currency_symbol', 10, 2);
    function bittube_add_currency_symbol($currency_symbol, $currency) {
        switch ($currency) {
        case 'BitTube':
            $currency_symbol = 'TUBE';
            break;
        }
        return $currency_symbol;
    }

    if(BitTube_Gateway::use_bittube_price()) {

        // This filter will replace all prices with amount in BitTube (live rates)
        add_filter('wc_price', 'bittube_live_price_format', 10, 3);
        function bittube_live_price_format($price_html, $price_float, $args) {
            if(!isset($args['currency']) || !$args['currency']) {
                global $woocommerce;
                $currency = strtoupper(get_woocommerce_currency());
            } else {
                $currency = strtoupper($args['currency']);
            }
            return BitTube_Gateway::convert_wc_price($price_float, $currency);
        }

        // These filters will replace the live rate with the exchange rate locked in for the order
        // We must be careful to hit all the hooks for price displays associated with an order,
        // else the exchange rate can change dynamically (which it should for an order)
        add_filter('woocommerce_order_formatted_line_subtotal', 'bittube_order_item_price_format', 10, 3);
        function bittube_order_item_price_format($price_html, $item, $order) {
            return BitTube_Gateway::convert_wc_price_order($price_html, $order);
        }

        add_filter('woocommerce_get_formatted_order_total', 'bittube_order_total_price_format', 10, 2);
        function bittube_order_total_price_format($price_html, $order) {
            return BitTube_Gateway::convert_wc_price_order($price_html, $order);
        }

        add_filter('woocommerce_get_order_item_totals', 'bittube_order_totals_price_format', 10, 3);
        function bittube_order_totals_price_format($total_rows, $order, $tax_display) {
            foreach($total_rows as &$row) {
                $price_html = $row['value'];
                $row['value'] = BitTube_Gateway::convert_wc_price_order($price_html, $order);
            }
            return $total_rows;
        }

    }

    add_action('wp_enqueue_scripts', 'bittube_enqueue_scripts');
    function bittube_enqueue_scripts() {
        if(BitTube_Gateway::use_bittube_price())
            wp_dequeue_script('wc-cart-fragments');
        if(BitTube_Gateway::use_qr_code())
            wp_enqueue_script('bittube-qr-code', BITTUBE_GATEWAY_PLUGIN_URL.'assets/js/qrcode.min.js');
			wp_enqueue_script('bittube-clipboard-js', BITTUBE_GATEWAY_PLUGIN_URL.'assets/js/clipboard.min.js');
			wp_enqueue_script('bittube-gateway', BITTUBE_GATEWAY_PLUGIN_URL.'assets/js/bittube-gateway-order-page.js');
			wp_enqueue_style('bittube-gateway', BITTUBE_GATEWAY_PLUGIN_URL.'assets/css/bittube-gateway-order-page.css');
    }

    // [bittube-price currency="USD"]
    // currency: BTC, GBP, etc
    // if no none, then default store currency
    function bittube_price_func( $atts ) {
        global  $woocommerce;
        $a = shortcode_atts( array(
            'currency' => get_woocommerce_currency()
        ), $atts );

        $currency = strtoupper($a['currency']);
        $rate = BitTube_Gateway::get_live_rate($currency);
        if($currency == 'BTC')
            $rate_formatted = sprintf('%.8f', $rate / 1e8);
        else
            $rate_formatted = sprintf('%.5f', $rate / 1e8);

        return "<span class=\"bittube-price\">1 TUBE = $rate_formatted $currency</span>";
    }
    add_shortcode('bittube-price', 'bittube_price_func');


    // [bittube-accepted-here]
    function bittube_accepted_func() {
        return '<img src="'.BITTUBE_GATEWAY_PLUGIN_URL.'assets/images/bittube-accepted-here.png" />';
    }
    add_shortcode('bittube-accepted-here', 'bittube_accepted_func');

}

register_deactivation_hook(__FILE__, 'bittube_deactivate');
function bittube_deactivate() {
    $timestamp = wp_next_scheduled('bittube_update_event');
    wp_unschedule_event($timestamp, 'bittube_update_event');
}

register_activation_hook(__FILE__, 'bittube_install');
function bittube_install() {
    global $wpdb;
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . "bittube_gateway_quotes";
    if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
               order_id BIGINT(20) UNSIGNED NOT NULL,
               payment_id VARCHAR(64) DEFAULT '' NOT NULL,
               currency VARCHAR(6) DEFAULT '' NOT NULL,
               rate BIGINT UNSIGNED DEFAULT 0 NOT NULL,
               amount BIGINT UNSIGNED DEFAULT 0 NOT NULL,
               paid TINYINT NOT NULL DEFAULT 0,
               confirmed TINYINT NOT NULL DEFAULT 0,
               pending TINYINT NOT NULL DEFAULT 1,
               created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
               PRIMARY KEY (order_id)
               ) $charset_collate;";
        dbDelta($sql);
    }

    $table_name = $wpdb->prefix . "bittube_gateway_quotes_txids";
    if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
               id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
               payment_id VARCHAR(64) DEFAULT '' NOT NULL,
               txid VARCHAR(64) DEFAULT '' NOT NULL,
               amount BIGINT UNSIGNED DEFAULT 0 NOT NULL,
               height MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
               PRIMARY KEY (id),
               UNIQUE KEY (payment_id, txid, amount)
               ) $charset_collate;";
        dbDelta($sql);
    }

    $table_name = $wpdb->prefix . "bittube_gateway_live_rates";
    if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
               currency VARCHAR(6) DEFAULT '' NOT NULL,
               rate BIGINT UNSIGNED DEFAULT 0 NOT NULL,
               updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
               PRIMARY KEY (currency)
               ) $charset_collate;";
        dbDelta($sql);
    }
}
