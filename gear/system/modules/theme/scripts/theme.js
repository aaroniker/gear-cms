var $ = require('jquery');

$(function() {

    var scrolled;
    var lastScroll = 0;
    var delta = 5;
    var topElem = $('#main .top');

    var headerOffset = 24;
    $(window).scroll(function() {
        topElem.toggleClass('scroll', ((window.pageYOffset || document.documentElement.scrollTop) >= headerOffset));
        scrolled = true;
    });

    setInterval(function() {
        if(scrolled) {
            hasScrolled();
            scrolled = false;
        }
    }, 250);

    function hasScrolled() {
        var st = $(this).scrollTop();
        if(Math.abs(lastScroll - st) <= delta) {
            return;
        }
        if(st > lastScroll && st > topElem.outerHeight()) {
            topElem.removeClass('nav-down').addClass('nav-up');
        } else {
            if(st + $(window).height() < $(document).height()) {
                topElem.removeClass('nav-up').addClass('nav-down');
            }
        }
        lastScroll = st;
    }

});
