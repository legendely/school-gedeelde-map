/**
 * Created by Sergen on 25-8-2014.
 */
$(document).ready(function() {
    (function ($) {
        $(window).load(function () {
            $(".editbody").mCustomScrollbar({
                theme: "dark",
                alwaysShowScrollbar: 1,
                scrollButtons: {
                    enable: true
                }
            });
        });
    })(jQuery);
});