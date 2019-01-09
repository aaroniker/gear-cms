var $ = require('jquery');

$(function() {

    var menuBtn = $('#main .toolbar .menu');
    var menuToggle = menuBtn.children('[type="checkbox"]');

    menuToggle.on('change', function(e) {
        $('#sidebar, #main, body, html').toggleClass('openSide', $(this).is(':checked'));
    }).trigger('change');

    $(document).on('click', function(e) {
        if(menuToggle.is(':checked') && menuBtn !== e.target && !menuBtn.has(e.target).length && $('#sidebar') !== e.target && !$('#sidebar').has(e.target).length) {
            menuToggle.prop('checked', false);
            menuToggle.trigger('change');
        }
    });

    $('#sidebar > .inner > nav > ul > li > span').on('click', function(e) {
        var _this = $(this).parent();
        _this.children('ul').slideToggle(200, function(e) {
            _this.toggleClass('opened');
        });
    });

    $('.dropdown > span, .dropdown > .btn').on('click touch', function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('open');
    });

    $(document).on('click touch', function(e) {
        var dropdown = $('.dropdown');
        if(dropdown !== e.target && !dropdown.has(e.target).length) {
            dropdown.removeClass('open');
        }
    });

});
