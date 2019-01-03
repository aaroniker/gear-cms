var $ = require('jquery');

$(function() {

    $('#main .toolbar .menu [type="checkbox"]').on('change', function() {
        $('#sidebar, #main, body, html').toggleClass('openSide', $(this).is(':checked'));
    }).trigger('change');

});
