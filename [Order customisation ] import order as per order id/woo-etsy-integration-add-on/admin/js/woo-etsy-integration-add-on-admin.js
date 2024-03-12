(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).on('click', '#ced_etsy_etsy_orders_by_order_id', function(){
		var shop_name = $(this).data('shopname');
		var orders_idsss = $('#ced_etsy_fetch_order_by_order_id').val();
		$( '#wpbody-content' ).block(
			{
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			}
		);
		$.ajax({
			url : 'admin-ajax.php',
			data : {
				action    : 'ced_etsy_bulk_import_order_by_order_id',
				shop_name : shop_name,
				order_ids : orders_idsss,
			},
			type : 'POST',
			success : function(response) {
				$( '#wpbody-content' ).unblock();
				var response  = jQuery.parseJSON( response );
				var response1 = jQuery.trim( response.message );
				if (response1 == "Shop is Not Active") {
					var notice = "";
					notice    += "<div class='notice notice-error'><p>Currently Shop is not Active . Please activate your Shop in order to fetch orders.</p></div>";
					$( ".success-admin-notices" ).append( notice );
					return;
				} else {
					var notice = "";
					let noti_class = 'notice-success';
					if ( 400 === response.status ) {
						noti_class = 'notice-error';
					}
					if ( response.message ) {
						$( ".success-admin-notices" ).append( "<div class='notice "+ noti_class +"'><p>" + response.message + "</p></div>" );
					}
					   window.setTimeout( function() {window.location.reload();},2000 );
				
				}
                
			}
		});
	});

})( jQuery );
