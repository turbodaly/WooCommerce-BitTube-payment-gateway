<?php

defined( 'ABSPATH' ) || exit;

return array(
    'enabled' => array(
        'title' => __('Enable / Disable', 'bittube_gateway'),
        'label' => __('Enable this payment gateway', 'bittube_gateway'),
        'type' => 'checkbox',
        'default' => 'no'
    ),
    'title' => array(
        'title' => __('Title', 'bittube_gateway'),
        'type' => 'text',
        'desc_tip' => __('Payment title the customer will see during the checkout process.', 'bittube_gateway'),
        'default' => __('BitTube Gateway', 'bittube_gateway')
    ),
    'description' => array(
        'title' => __('Description', 'bittube_gateway'),
        'type' => 'textarea',
        'desc_tip' => __('Payment description the customer will see during the checkout process.', 'bittube_gateway'),
        'default' => __('Pay securely using BitTube. You will be provided payment details after checkout.', 'bittube_gateway')
    ),
    'discount' => array(
        'title' => __('Discount for using BitTube', 'bittube_gateway'),
        'desc_tip' => __('Provide a discount to your customers for making a private payment with BitTube', 'bittube_gateway'),
        'description' => __('Enter a percentage discount (i.e. 5 for 5%) or leave this empty if you do not wish to provide a discount', 'bittube_gateway'),
        'type' => __('number'),
        'default' => '0'
    ),
    'valid_time' => array(
        'title' => __('Order valid time', 'bittube_gateway'),
        'desc_tip' => __('Amount of time order is valid before expiring', 'bittube_gateway'),
        'description' => __('Enter the number of seconds that the funds must be received in after order is placed. 3600 seconds = 1 hour', 'bittube_gateway'),
        'type' => __('number'),
        'default' => '3600'
    ),
    'confirms' => array(
        'title' => __('Number of confirmations', 'bittube_gateway'),
        'desc_tip' => __('Number of confirms a transaction must have to be valid', 'bittube_gateway'),
        'description' => __('Enter the number of confirms that transactions must have. Enter 0 to zero-confim. Each confirm will take approximately four minutes', 'bittube_gateway'),
        'type' => __('number'),
        'default' => '5'
    ),
    'confirm_type' => array(
        'title' => __('Confirmation Type', 'bittube_gateway'),
        'desc_tip' => __('', 'bittube_gateway'),
        'description' => __('', 'bittube_gateway'),
        'type' => 'select',
        'options' => array(
            'viewkey'        => __('viewkey', 'bittube_gateway'),
        ),
        'default' => 'viewkey'
    ),
    'bittube_address' => array(
        'title' => __('BitTube Address', 'bittube_gateway'),
        'label' => __('Useful for people that have not a daemon online'),
        'type' => 'text',
        'desc_tip' => __('BitTube Wallet Address (BitTubeL)', 'bittube_gateway')
    ),
    'viewkey' => array(
        'title' => __('Secret Viewkey', 'bittube_gateway'),
        'label' => __('Secret Viewkey'),
        'type' => 'text',
        'desc_tip' => __('Your secret Viewkey', 'bittube_gateway')
    ),
    'daemon_host' => array(
        'title' => __('BitTube wallet RPC Host/IP', 'bittube_gateway'),
        'type' => 'text',
        'desc_tip' => __('This is the Daemon Host/IP to authorize the payment with', 'bittube_gateway'),
        'default' => '127.0.0.1',
    ),
    'daemon_port' => array(
        'title' => __('BitTube wallet RPC port', 'bittube_gateway'),
        'type' => __('number'),
        'desc_tip' => __('This is the Wallet RPC port to authorize the payment with', 'bittube_gateway'),
        'default' => '18080',
    ),
    'testnet' => array(
        'title' => __(' Testnet', 'bittube_gateway'),
        'label' => __(' Check this if you are using testnet ', 'bittube_gateway'),
        'type' => 'checkbox',
        'description' => __('Advanced usage only', 'bittube_gateway'),
        'default' => 'no'
    ),
    'onion_service' => array(
        'title' => __(' SSL warnings ', 'bittube_gateway'),
        'label' => __(' Check to Silence SSL warnings', 'bittube_gateway'),
        'type' => 'checkbox',
        'description' => __('Check this box if you are running on an Onion Service (Suppress SSL errors)', 'bittube_gateway'),
        'default' => 'no'
     ),
	    'show_qr' => array(
        'title' => __('Show QR Code', 'bittube_gateway'),
        'label' => __('Show QR Code', 'bittube_gateway'),
        'type' => 'checkbox',
        'description' => __('Enable this to show a QR code after checkout with payment details.'),
        'default' => 'yes'
    ),
    'use_bittube_price' => array(
        'title' => __('Show Prices in BitTube', 'bittube_gateway'),
        'label' => __('Show Prices in BitTube', 'bittube_gateway'),
        'type' => 'checkbox',
        'description' => __('Enable this to convert ALL prices on the frontend to BitTube (experimental)'),
        'default' => 'no'
    ),
    'use_bittube_price_decimals' => array(
        'title' => __('Display Decimals', 'bittube_gateway'),
        'type' => __('number'),
        'description' => __('Number of decimal places to display on frontend. Upon checkout exact price will be displayed.'),
        'default' => 12,
    ),
);
