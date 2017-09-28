(function() {
	tinymce.PluginManager.add('shaplatools_portfolio_mce_button', function( editor, url ) {
		editor.addButton( 'shaplatools_portfolio_mce_button', {

            title : 'Add Shapla Portfolio',
			image : url + '/icon-portfolio.png',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Portfolio Shortcode',
					body: 
					[
						{
							type: 'listbox',
							name: 'thumbnail',
							label: 'Thumbnail per row',
								'values': [
									{text: 'Show 2 thumbnail per row', value: '2'},
									{text: 'Show 3 thumbnail per row', value: '3'},
									{text: 'Show 4 thumbnail per row', value: '4'},
									{text: 'Show 5 thumbnail per row', value: '5'}
								]
						},
						{
							type: 'textbox',
							name: 'thumbnail_size',
							label: 'Portfolio Image Size Name',
							value: 'full'
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[shapla_portfolio thumbnail="' + e.data.thumbnail + '" thumbnail_size="' + e.data.thumbnail_size + '"]');
						}
					}
				);
			}

		});
	});
})();