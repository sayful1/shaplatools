( function($) {
	$( '.woocommerce-product-search input[name="s"]' )
		.typeahead( {
			name: 'search',
			remote: wp_typeahead.ajaxurl + '?action=ajax_search&fn=get_ajax_search&terms=%QUERY',
			template: [
				'<p><a href="{{url}}"><span class="s-img"><img src="{{img_url}}"></span><span class="s-title">{{value}}</span></a></p>',
			].join(''),
			engine: Hogan
		} )
		.keypress( function(e) {
			if ( 13 == e.which ) {
				$(this).parents( 'form' ).submit();
				return false;
			}
		}
	);
} )(jQuery);
