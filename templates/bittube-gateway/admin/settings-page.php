<?php foreach($errors as $error): ?>
<div class="error"><p><strong>BitTube Gateway Error</strong>: <?php echo $error; ?></p></div>
<?php endforeach; ?>

<h1>BitTube Gateway Settings</h1>

<?php if($confirm_type === 'bittube-wallet-rpc'): ?>
<div style="border:1px solid #ddd;padding:5px 10px;">
    <?php
         echo 'Wallet height: ' . $balance['height'] . '</br>';
         echo 'Your balance is: ' . $balance['balance'] . '</br>';
         echo 'Unlocked balance: ' . $balance['unlocked_balance'] . '</br>';
         ?>
</div>
<?php endif; ?>

<table class="form-table">
    <?php echo $settings_html ?>
</table>

<h4><a href="https://bittubeintegrations.com/">Learn more about using the BitTube payment gateway</a></h4>

<script>
function bittubeUpdateFields() {
    var confirmType = jQuery("#woocommerce_bittube_gateway_confirm_type").val();
    if(confirmType == "bittube-wallet-rpc") {
        jQuery("#woocommerce_bittube_gateway_bittube_address").closest("tr").hide();
        jQuery("#woocommerce_bittube_gateway_viewkey").closest("tr").hide();
        jQuery("#woocommerce_bittube_gateway_daemon_host").closest("tr").show();
        jQuery("#woocommerce_bittube_gateway_daemon_port").closest("tr").show();
    } else {
        jQuery("#woocommerce_bittube_gateway_bittube_address").closest("tr").show();
        jQuery("#woocommerce_bittube_gateway_viewkey").closest("tr").show();
        jQuery("#woocommerce_bittube_gateway_daemon_host").closest("tr").hide();
        jQuery("#woocommerce_bittube_gateway_daemon_port").closest("tr").hide();
    }
    var useBitTubePrices = jQuery("#woocommerce_bittube_gateway_use_bittube_price").is(":checked");
    if(useBitTubePrices) {
        jQuery("#woocommerce_bittube_gateway_use_bittube_price_decimals").closest("tr").show();
    } else {
        jQuery("#woocommerce_bittube_gateway_use_bittube_price_decimals").closest("tr").hide();
    }
}
bittubeUpdateFields();
jQuery("#woocommerce_bittube_gateway_confirm_type").change(bittubeUpdateFields);
jQuery("#woocommerce_bittube_gateway_use_bittube_price").change(bittubeUpdateFields);
</script>

<style>
#woocommerce_bittube_gateway_bittube_address,
#woocommerce_bittube_gateway_viewkey {
    width: 100%;
}
</style>