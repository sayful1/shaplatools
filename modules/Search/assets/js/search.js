(function ($) {
    'use strict';

    var product_categories = $('#product_cat'),
        product_cat = product_categories.val();

    $('[name="s"]').devbridgeAutocomplete({
        minChars: 3,
        zIndex: 999999,
        paramName: 's',
        appendTo: '.shapla-search',
        serviceUrl: shaplatools.ajaxurl,
        params: {
            action: 'shaplatools_search',
            search_nonce: shaplatools.nonce,
            product_cat: product_cat
        },
        onSelect: function (suggestion) {
            if (suggestion.id !== -1) {
                window.location.href = suggestion.url;
            }
        }
    });

    product_categories.on('change', function () {
        $('[name="s"]').devbridgeAutocomplete().setOptions({
            params: {
                product_cat: $(this).val()
            }
        });
    });

})(jQuery);