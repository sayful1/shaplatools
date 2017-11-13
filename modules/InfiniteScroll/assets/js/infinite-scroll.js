/**
 * Infinite Scroll
 *
 * Most of the code has been taken from
 * YITH Infinite Scrolling
 * @link https://wordpress.org/plugins/yith-infinite-scrolling/
 */
(function ($, window, document) {
    "use strict";

    $.fn.shaplaToolsInfiniteScroll = function (options) {

        var opts = $.extend({
                nextSelector: false,
                navSelector: false,
                itemSelector: false,
                contentSelector: false,
                maxPage: false,
                loader: false,
                is_shop: false

            }, options),

            loading = false,
            finished = false,
            destination_url = $(opts.nextSelector).attr('href'); // init next url

        // validate options and hide std navigation
        if ($(opts.nextSelector).length && $(opts.navSelector).length && $(opts.itemSelector).length && $(opts.contentSelector).length) {
            $(opts.navSelector).hide();
        } else {
            // set finished true
            finished = true;
        }

        // set elem columns ( in shop page )
        var first_elem = $(opts.contentSelector).find(opts.itemSelector).first(),
            columns = first_elem.nextUntil('.first', opts.itemSelector).length + 1;

        var main_ajax = function () {

            var last_elem = $(opts.contentSelector).find(opts.itemSelector).last();
            // set loader and loading
            if (opts.loader)
                $(opts.navSelector).after('<div class="shaplatools-infs-loader">' + opts.loader + '</div>');
            loading = true;
            // decode url to prevent error
            destination_url = decodeURIComponent(destination_url);
            destination_url = destination_url.replace(/^(?:\/\/|[^\/]+)*\//, "/");

            // ajax call
            $.ajax({
                // params
                url: destination_url,
                dataType: 'html',
                cache: false,
                success: function (data) {

                    var obj = $(data),
                        elem = obj.find(opts.itemSelector),
                        next = obj.find(opts.nextSelector),
                        current_url = destination_url;

                    if (next.length) {
                        destination_url = next.attr('href');
                    }
                    else {
                        // set finished var true
                        finished = true;
                        $(document).trigger('shaplatools-infs-scroll-finished');
                    }

                    // recalculate element position in shop
                    if (!last_elem.hasClass('last') && opts.is_shop) {
                        position_elem(last_elem, columns, elem);
                    }

                    last_elem.after(elem);

                    $('.shaplatools-infs-loader').remove();

                    $(document).trigger('shaplatools_infs_adding_elem', [elem, current_url]);

                    elem.addClass('shaplatools-infs-animated');

                    setTimeout(function () {
                        loading = false;
                        // remove animation class
                        elem.removeClass('shaplatools-infs-animated');

                        $(document).trigger('shaplatools_infs_added_elem', [elem, current_url]);

                    }, 1000);

                }
            });
        };

        // recalculate element position
        var position_elem = function (last, columns, elem) {


            var offset = ( columns - last.prevUntil('.last', opts.itemSelector).length ),
                loop = 0;

            elem.each(function () {

                var t = $(this);
                loop++;

                t.removeClass('first');
                t.removeClass('last');

                if (( ( loop - offset ) % columns ) === 0) {
                    t.addClass('first');
                }
                else if (( ( loop - ( offset - 1 ) ) % columns ) === 0) {
                    t.addClass('last');
                }
            });
        };

        // set event
        $(window).on('scroll touchstart', function () {
            $(this).trigger('shaplatools_infs_start');
        });

        $(window).on('shaplatools_infs_start', function () {
            var t = $(this),
                elem = $(opts.itemSelector).last();

            if (typeof elem === 'undefined') {
                return;
            }

            if (!loading && !finished && ( t.scrollTop() + t.height() ) >= ( elem.offset().top + elem.height() )) {
                main_ajax();
            }
        })
    }

})(jQuery, window, document);


/**
 * Implementation of Infinite Scroll
 * @global ShaplaToolsInfiniteScroll
 */
jQuery(document).ready(function ($) {
    "use strict";

    if (typeof ShaplaToolsInfiniteScroll === 'undefined') {
        return;
    }

    // set options
    var infinite_scroll = {
        'nextSelector': ShaplaToolsInfiniteScroll.nextSelector,
        'navSelector': ShaplaToolsInfiniteScroll.navSelector,
        'itemSelector': ShaplaToolsInfiniteScroll.itemSelector,
        'contentSelector': ShaplaToolsInfiniteScroll.contentSelector,
        'loader': '<img src="' + ShaplaToolsInfiniteScroll.loader + '">',
        'is_shop': ShaplaToolsInfiniteScroll.shop
    };

    $(ShaplaToolsInfiniteScroll.contentSelector).shaplaToolsInfiniteScroll(infinite_scroll);

    $(document).on('yith-wcan-ajax-filtered', function () {
        // reset
        $(window).unbind('shaplatools_infs_start');
        $(ShaplaToolsInfiniteScroll.contentSelector).shaplaToolsInfiniteScroll(infinite_scroll);
    });
});