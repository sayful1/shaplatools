(function() {
	tinymce.PluginManager.add('shaplatools_feature_mce_button', function( editor, url ) {
		editor.addButton( 'shaplatools_feature_mce_button', {

            title : 'Add Shapla Feature',
			image : url + '/icon-features.png',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Feature Shortcode',
					body: 
					[
						{
							type: 'listbox',
							name: 'thumbnail',
							label: 'Feature per row',
								'values': [
									{text: 'Show 2 features per row', value: '2'},
									{text: 'Show 3 features per row', value: '3'},
									{text: 'Show 4 features per row', value: '4'},
									{text: 'Show 5 features per row', value: '5'}
								]
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[shapla_feature thumbnail="' + e.data.thumbnail + '"]');
						}
					}
				);
			}

		});
	});
})();