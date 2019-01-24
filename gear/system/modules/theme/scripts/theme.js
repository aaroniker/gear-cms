let $ = require('jquery');

$(() => {

    let menuBtn = $('#main .toolbar .menu'),
        menuToggle = menuBtn.children('[type="checkbox"]');

    menuToggle.on('change', e => {
        let _this = $(e.currentTarget);
        $('#sidebar, #main, body, html').toggleClass('openSide', _this.is(':checked'));
    }).trigger('change');

    $(document).on('click', e => {
        if(menuToggle.is(':checked') && menuBtn !== e.target && !menuBtn.has(e.target).length && $('#sidebar') !== e.target && !$('#sidebar').has(e.target).length) {
            menuToggle.prop('checked', false);
            menuToggle.trigger('change');
        }
    });

    $('#sidebar > .inner > nav > ul > li > span').on('click', e => {
        let _this = $(e.currentTarget).parent();
        _this.children('ul').slideToggle(200, e => {
            _this.toggleClass('opened');
        });
    });

});
