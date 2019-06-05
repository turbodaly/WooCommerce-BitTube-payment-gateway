/*
 * Copyright (c) 2018, BitTube
*/
function bittube_showNotification(message, type='success') {
    var toast = jQuery('<div class="' + type + '"><span>' + message + '</span></div>');
    jQuery('#bittube_toast').append(toast);
    toast.animate({ "right": "12px" }, "fast");
    setInterval(function() {
        toast.animate({ "right": "-400px" }, "fast", function() {
            toast.remove();
        });
    }, 2500)
}
function bittube_showQR(show=true) {
    jQuery('#bittube_qr_code_container').toggle(show);
}
function bittube_fetchDetails() {
    var data = {
        '_': jQuery.now(),
        'order_id': bittube_details.order_id
    };
    jQuery.get(bittube_ajax_url, data, function(response) {
        if (typeof response.error !== 'undefined') {
            console.log(response.error);
        } else {
            bittube_details = response;
            bittube_updateDetails();
        }
    });
}

function bittube_updateDetails() {

    var details = bittube_details;

    jQuery('#bittube_payment_messages').children().hide();
    switch(details.status) {
        case 'unpaid':
            jQuery('.bittube_payment_unpaid').show();
            jQuery('.bittube_payment_expire_time').html(details.order_expires);
            break;
        case 'partial':
            jQuery('.bittube_payment_partial').show();
            jQuery('.bittube_payment_expire_time').html(details.order_expires);
            break;
        case 'paid':
            jQuery('.bittube_payment_paid').show();
            jQuery('.bittube_confirm_time').html(details.time_to_confirm);
            jQuery('.button-row button').prop("disabled",true);
            break;
        case 'confirmed':
            jQuery('.bittube_payment_confirmed').show();
            jQuery('.button-row button').prop("disabled",true);
            break;
        case 'expired':
            jQuery('.bittube_payment_expired').show();
            jQuery('.button-row button').prop("disabled",true);
            break;
        case 'expired_partial':
            jQuery('.bittube_payment_expired_partial').show();
            jQuery('.button-row button').prop("disabled",true);
            break;
    }

    jQuery('#bittube_exchange_rate').html('1 TUBE = '+details.rate_formatted+' '+details.currency);
    jQuery('#bittube_total_amount').html(details.amount_total_formatted);
    jQuery('#bittube_total_paid').html(details.amount_paid_formatted);
    jQuery('#bittube_total_due').html(details.amount_due_formatted);
	jQuery('#bittube_integrated_address').html(details.integrated_address);
    jQuery('#bittube_address').html(details.address);
	jQuery('#bittube_payment_id').html(details.payment_id);
	jQuery('#bittube_confirmations').html(details.confirmations);
	
    if(bittube_show_qr) {
        var qr = jQuery('#bittube_qr_code').html('');
        new QRCode(qr.get(0), details.qrcode_uri);
    }

    if(details.txs.length) {
        jQuery('#bittube_tx_table').show();
        jQuery('#bittube_tx_none').hide();
        jQuery('#bittube_tx_table tbody').html('');
        for(var i=0; i < details.txs.length; i++) {
            var tx = details.txs[i];
            var height = tx.height == 0 ? 'N/A' : tx.height;
            var row = ''+
                '<tr>'+
                '<td style="word-break: break-all">'+
                '<a href="'+bittube_explorer_url+'/tx/'+tx.txid+'" target="_blank">'+tx.txid+'</a>'+
                '</td>'+
                '<td>'+height+'</td>'+
                '<td>'+tx.amount_formatted+' BitTube</td>'+
                '</tr>';

            jQuery('#bittube_tx_table tbody').append(row);
        }
    } else {
        jQuery('#bittube_tx_table').hide();
        jQuery('#bittube_tx_none').show();
    }

    // Show state change notifications
    var new_txs = details.txs;
    var old_txs = bittube_order_state.txs;
    if(new_txs.length != old_txs.length) {
        for(var i = 0; i < new_txs.length; i++) {
            var is_new_tx = true;
            for(var j = 0; j < old_txs.length; j++) {
                if(new_txs[i].txid == old_txs[j].txid && new_txs[i].amount == old_txs[j].amount) {
                    is_new_tx = false;
                    break;
                }
            }
            if(is_new_tx) {
                bittube_showNotification('Transaction received for '+new_txs[i].amount_formatted+' BitTube');
            }
        }
    }

    if(details.status != bittube_order_state.status) {
        switch(details.status) {
            case 'paid':
                bittube_showNotification('Your order has been paid in full');
                break;
            case 'confirmed':
                bittube_showNotification('Your order has been confirmed');
                break;
            case 'expired':
            case 'expired_partial':
                bittube_showNotification('Your order has expired', 'error');
                break;
        }
    }

    bittube_order_state = {
        status: bittube_details.status,
        txs: bittube_details.txs
    };

}
jQuery(document).ready(function($) {
    if (typeof bittube_details !== 'undefined') {
        bittube_order_state = {
            status: bittube_details.status,
            txs: bittube_details.txs
        };
        setInterval(bittube_fetchDetails, 30000);
        bittube_updateDetails();
        new ClipboardJS('.clipboard').on('success', function(e) {
            e.clearSelection();
            if(e.trigger.disabled) return;
            switch(e.trigger.getAttribute('data-clipboard-target')) {
                case '#bittube_address':
                    bittube_showNotification('Copied destination address!');
                    break;
			   case '#bittube_payment_id':
                    bittube_showNotification('Copied Payment ID!');
                    break;
                case '#bittube_total_due':
                    bittube_showNotification('Copied total amount due!');
                    break;
            }
            e.clearSelection();
        });
    }
});