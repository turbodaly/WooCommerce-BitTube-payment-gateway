<section class="woocommerce-order-details">
    <h2 class="woocommerce-order-details__title"><?php echo $method_title ?></h2>
    <noscript><h1>You must enable javascript in order to confirm your order</h1></noscript>
	</section><p>
		
	<img src="<?php echo plugin_dir_url( __FILE__ ) . 'bittube-accepted-here.png'; ?>"></p>
    <strong id="bittube_payment_messages">

        <span class="bittube_payment_unpaid"><font color=DC143C>Please pay the amount due to complete your transactions. Your order will expire in <span class="bittube_payment_expire_time"></span> if payment is not received.</font></span>

        <span class="bittube_payment_partial"><font color= #46A4D9>We have received partial payment. Please pay the remaining amount to complete your transactions. Your order will expire in <span class="bittube_payment_expire_time"></span> if payment is not received.</font></span>

        <span class="bittube_payment_paid"><font color=006400>We have received your payment in full. Please wait while amount is confirmed. Approximate confirm time is <span class="bittube_confirm_time"></span>. <span class="bittube_confirmations"></span> <?php if(is_wc_endpoint_url('order-received')): ?><br/>You can <a href="<?php echo $details['my_order_url']; ?>">check your payment status</a> anytime in your account dashboard.<?php endif; ?></font></span>

        <span class="bittube_payment_confirmed"><font color=006400>Your order has been confirmed. Thank you for paying with BitTube!</font></span>

        <span class="bittube_payment_expired"><font color=DC143C>Your order has expired. Please place another order to complete your purchase.</font></span>

        <span class="bittube_payment_expired_partial"><font color=DC143C>Your order has expired. Please contact the store owner to receive refund on your partial payment.</font></span>

    </strong>
	

<table >
	<tbody>
		<tr>
			<td>Send </td>
			<td><strong> <span id="bittube_total_due"></span> TUBE
                </span></strong</td>
			<td> <button href="#" class="button clipboard" title="Copy Amount"
                            data-clipboard-target="#bittube_total_due">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512" version="1"><path d="M504 118c-6-6-12-8-20-8H365c-11 0-23 3-36 11V27c0-7-3-14-8-19s-12-8-20-8H183c-8 0-16 2-25 6-10 4-17 8-22 13L19 136c-5 5-9 12-13 22-4 9-6 17-6 25v192c0 7 3 14 8 19s12 8 19 8h156v82c0 8 2 14 8 20 5 5 12 8 19 8h274c8 0 14-3 20-8 5-6 8-12 8-20V137c0-8-3-14-8-19zm-175 52v86h-85l85-86zM146 61v85H61l85-85zm56 185c-5 5-10 12-14 21-3 9-5 18-5 25v73H37V183h118c8 0 14-3 20-8 5-6 8-12 8-20V37h109v118l-90 91zm273 229H219V292h119c8 0 14-2 19-8 6-5 8-11 8-19V146h110v329z"/></svg>
                    </button> </td>
		</tr>
		<tr>
			<td>Address </td>
			<td>  <strong><span class="bittube_details_main" id="bittube_address">  </strong></td>
			<td><button href="#" class="button clipboard" title="Copy Address"
                            data-clipboard-target="#bittube_address">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512" version="1"><path d="M504 118c-6-6-12-8-20-8H365c-11 0-23 3-36 11V27c0-7-3-14-8-19s-12-8-20-8H183c-8 0-16 2-25 6-10 4-17 8-22 13L19 136c-5 5-9 12-13 22-4 9-6 17-6 25v192c0 7 3 14 8 19s12 8 19 8h156v82c0 8 2 14 8 20 5 5 12 8 19 8h274c8 0 14-3 20-8 5-6 8-12 8-20V137c0-8-3-14-8-19zm-175 52v86h-85l85-86zM146 61v85H61l85-85zm56 185c-5 5-10 12-14 21-3 9-5 18-5 25v73H37V183h118c8 0 14-3 20-8 5-6 8-12 8-20V37h109v118l-90 91zm273 229H219V292h119c8 0 14-2 19-8 6-5 8-11 8-19V146h110v329z"/></svg>
                    </button> </td>
		</tr>
		<tr>
			<td>Payment ID </td>
			<td> <strong><span class="bittube_details_main" id="bittube_payment_id"></span></strong>  </td>
			<td><button href="#" class="button clipboard" title="Copy Payment ID"
                            data-clipboard-target="#bittube_payment_id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512" version="1"><path d="M504 118c-6-6-12-8-20-8H365c-11 0-23 3-36 11V27c0-7-3-14-8-19s-12-8-20-8H183c-8 0-16 2-25 6-10 4-17 8-22 13L19 136c-5 5-9 12-13 22-4 9-6 17-6 25v192c0 7 3 14 8 19s12 8 19 8h156v82c0 8 2 14 8 20 5 5 12 8 19 8h274c8 0 14-3 20-8 5-6 8-12 8-20V137c0-8-3-14-8-19zm-175 52v86h-85l85-86zM146 61v85H61l85-85zm56 185c-5 5-10 12-14 21-3 9-5 18-5 25v73H37V183h118c8 0 14-3 20-8 5-6 8-12 8-20V37h109v118l-90 91zm273 229H219V292h119c8 0 14-2 19-8 6-5 8-11 8-19V146h110v329z"/></svg>
                    </button> </td>
					<tr>
						<th colspan="2">Show QR Code</th>
			
			<td>   <button href="#" class="button" title="Show QR Code" onclick="bittube_showQR()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512" version="1"><path d="M0 512h233V279H0zm47-186h139v139H47z"/><path d="M93 372h47v47H93zm279 93h47v47h-47zm93 0h47v47h-47z"/><path d="M465 326h-46v-47H279v233h47V372h46v47h140V279h-47zM0 233h233V0H0zM47 47h139v139H47z"/><path d="M93 93h47v47H93zM279 0v233h233V0zm186 186H326V47h139z"/><path d="M372 93h47v47h-47z"/></svg>
                    </button></td>
		</tr>		
		
		<table >
	<tbody>
		<tr>
			<td>Exchange Rate </td>
			<td><strong id="bittube_exchange_rate"></strong> </td>
			<td>Total Paid </td>
			<td> <strong> <span id="bittube_total_paid">TUBE</span></strong>  </td>
		</tr>	</tbody>
</table>
	</tbody>
</table>

<table >
	<tbody>
		
	</tbody>
</table>
  




    <table id="bittube_tx_table" style="display:none;">
        <thead>
            <tr>
                <th>Transaction id</th>
                <th>Height</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div id="bittube_tx_none" style="display:none;">
    </div>

    <div id="bittube_qr_code_container" style="display:none;" onclick="bittube_showQR(false)">
        <div id="bittube_qr_code">
        </div>
    </div>

</section>

<div id="bittube_toast"></div>

<script type="text/javascript">
    var bittube_show_qr = <?php echo $show_qr ? 'true' : 'false'; ?>;
    var bittube_ajax_url = '<?php echo $ajax_url; ?>';
    var bittube_explorer_url = '<?php echo BITTUBE_GATEWAY_EXPLORER_URL; ?>';
    var bittube_details = <?php echo $details_json; ?>;
</script>
