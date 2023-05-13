(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	jQuery(document).ready(function ($) {

		$(document).on('click', '.certificate_search_row_btn_search', function (e) {
			e.preventDefault();
			var certificateNo = $('input[name="certificate"]').val();
			var serialNo = $('input[name="serial"]').val();
			$('.loading_screen_container').css("display", "flex");
			jQuery.ajax({
				type: "POST",
				url: 'wp-admin/admin-ajax.php',
				data: {
					action: "certificate_search_result",
					certificate_no: certificateNo,
					serial_no: serialNo,
				},
				dataType: "html",
				cache: false,
				success: function (res) {
					$('.loading_screen_container').hide();
					$("#certificate_search_table").empty().append(res);
				}
			});
		});

		$(document).on('click', '.more_subscription', function (e) {
			e.preventDefault();
			const certificateNo = $('input[name="certificate"]').val();
			const serialNo = $('input[name="serial"]').val();
			$('.loading_screen_container').show();
			$.ajax({
				type: "POST",
				url: 'wp-admin/admin-ajax.php',
				data: {
					action: "certificate_search_subscription_result",
					certificate_no: certificateNo,
					serial_no: serialNo,
				},
				dataType: "html",
				cache: false,
				success: function (res) {
					$('.loading_screen_container').hide();
					$(".to_append").append(res);
				}
			});
		});

		$(".certificate_field").hover(function(){
			$('.certificate_field_img').toggle();
		});

		$(".serial_field").hover(function(){
			$('.serial_field_img').toggle();
		});
	});

})( jQuery );
