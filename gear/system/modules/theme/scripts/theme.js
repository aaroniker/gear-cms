var $ = require('jquery');

$(function() {

    $('#main .toolbar .menu [type="checkbox"]').on('change', function() {
        $('#sidebar, #main, body, html').toggleClass('openSide', $(this).is(':checked'));
    }).trigger('change');

    $('#sidebar > .inner > nav > ul > li > span').on('click', function(e) {
        var _this = $(this).parent();
        _this.children('ul').slideToggle(200, function() {
            _this.toggleClass('opened');
        });
    });

});
