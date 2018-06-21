

( function ($) {
	'use strict';
	$( document ).ready(function () {		
		// Set manual event previous for carousel left control
		$('.st-element-carousel .left').on('click', function (e) {
			e.preventDefault();
			var parent_id = $(this).closest('.carousel').attr('id');
			if ( typeof( $('#' + parent_id ).carousel ) == 'function' ) {
				$('#' + parent_id ).carousel( 'prev' );
			}
		});
		
		// Set manual event next for carousel right control
		$('.st-element-carousel .right').on('click', function (e) {
			e.preventDefault();
			var parent_id = $(this).closest('.carousel').attr('id');
			if ( typeof( $('#' + parent_id ).carousel ) == 'function' ) {
				$('#' + parent_id ).carousel( 'next' );
			}
		});
		
		// Set manual event for carousel indicator controls
		$('.st-element-carousel .carousel-indicators li').each(function (index) {
			$(this).on('click', function (e) {
				e.preventDefault();
				var parent_id = $(this).closest('.carousel').attr('id');
				if ( typeof( $('#' + parent_id ).carousel ) == 'function' ) {
					$('#' + parent_id ).carousel( index );
				}
			});
		});
	});
} )(jQuery);