// as the page loads, call these scripts
jQuery(document).ready(function($) 
{
	var blog2_layout_mode;
	$('div.cbb-blog2-instance').each(function ()
	{
		blog2_layout_mode = $(this).data('layout_mode');
		if(! blog2_layout_mode) return true;
		
		var isotope = $('.cbb-blog2', $(this));
		isotope.isotope({
			// options
			itemSelector:'.isotope-blog2-item',
			layoutMode: blog2_layout_mode
		});
		jQuery(window).load(function () {
			isotope.isotope("layout");
		});
	});
}); /* end of as page load scripts */
( function ($)
{
	"use strict";

	$.ST_Blog2 = function () {
		if (typeof $.IGSelectFonts != 'undefined') { new $.IGSelectFonts(); }

		$('#param-font').on('change', function () {
			if ($(this).val() == 'inherit') {
				$('#param-font_face_type').val('standard fonts');
				$('.jsn-fontFaceType').trigger('change');
				$('#param-font_size_value_').val('');
				$('#param-font_style').val('bold');
			}
		});
	}

	$(document).ready(function () {
		$.ST_Blog2();
	});

})(jQuery);