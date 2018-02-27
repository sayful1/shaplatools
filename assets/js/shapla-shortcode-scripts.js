(function ($) {
    "use strict";

    var Shaplatools = {};

    // Shapla Tab Shortcode
    $(".shapla-tabs").tabs({
        hide: {
            effect: "fadeOut",
            duration: 200
        },
        show: {
            effect: "fadeIn",
            duration: 200
        }
    });

    // Shapla Toggle Shortcode
    $(".shapla-toggle").each(function () {
        if ($(this).data('id') === 'closed') {
            $(this).accordion({
                header: '.shapla-toggle-title',
                collapsible: true,
                heightStyle: "content",
                active: false
            });
        } else {
            $(this).accordion({
                header: '.shapla-toggle-title',
                collapsible: true,
                heightStyle: "content"
            });
        }
    });

    // Shapla Google Map Shortcode
    Shaplatools.Map = (function () {
        function setupMap(options) {
            var mapOptions, mapElement, map, marker;

            if (typeof google === 'undefined') return;

            mapOptions = {
                zoom: parseFloat(options.zoom),
                center: new google.maps.LatLng(options.center.lat, options.center.long),
                scrollwheel: false,
                styles: options.styles
            };

            mapElement = document.getElementById(options.id);
            map = new google.maps.Map(mapElement, mapOptions);

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(options.center.lat, options.center.long),
                map: map
            });
        }

        return {
            init: function (options) {
                setupMap(options);
            }
        }
    })();

    $(window).on('load', function () {
        $(".shapla-google-map").each(function () {
            Shaplatools.Map.init($(this).data('map_options'));
        });
    });

})(jQuery);
