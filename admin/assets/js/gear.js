jQuery(document).ready(function($) {

    $('.form-group input.form-field').focus(function(e) {
        $(this).parent().addClass('focus');
    });

    $('.form-group input.form-field').blur(function(e) {
        $(this).parent().removeClass('focus');
    });

    $('.form-group > span').click(function(e) {
        $(this).parent().children('input').focus();
    });

    $('.password .icon-preview').click(function(e) {
        if(!$(this).hasClass('active')) {
            $(this).parent().children('input').attr('type', 'text').focus();
        } else {
            $(this).parent().children('input').attr('type', 'password').focus();
        }
        $(this).toggleClass('active');
    });

    $('select.form-select').each(function(e) {

        var classes = $(this).attr('class'),
        id = $(this).attr('id'),
        name = $(this).attr('name'),
        value = $(this).find(':selected').text();

        var template =  '<div class="' + classes + '">';
        template += '<span>' + value + '</span>';
        template += '<div>';
        $(this).find('option').each(function() {
            template += '<span class="' + $(this).attr('class') + '" data-value="' + $(this).attr('value') + '">' + $(this).html() + '</span>';
        });
        template += '</div></div>';

        $(this).hide();
        $(this).before(template);

    });

    $('.form-select:not(.disabled) > span').on('click', function(e) {
        $('html').one('click',function(e) {
            $('.form-select').removeClass('open');
        });
        $(this).parent().toggleClass('open');
        e.stopPropagation();
    });

    $('.form-select > div > span').on('click', function(e) {
        var div = $(this).parent().parent();
        div.next('select').val($(this).data('value'));
        $(this).parent().children('span').removeClass('active');
        $(this).addClass('active');
        div.removeClass('open').children('span').text($(this).text());
    });

    $(".slider .drag").draggable({
        axis: 'x',
        containment: 'parent',
        drag: function(e, ui) {
            var slider = $(this).parent();
            slider.addClass('active');
            if(ui.position.left >= (slider.outerWidth() - $(this).outerWidth() * 2)) {
                slider.addClass('hover');
            } else {
                slider.removeClass('hover');
            }
        },
        stop: function(e, ui) {
            var slider = $(this).parent();
            if(ui.position.left < (slider.outerWidth() - $(this).outerWidth() * 2)) {
                $(this).animate({
                    left: 0
                })
                slider.removeClass('hover');
                slider.removeClass('active');
            } else {
                $(this).animate({
                    left: 0
                })
                slider.removeClass('hover');
                slider.removeClass('active');
                //success
            }
        }
    });

    setTab($('#tab1'));
    setTab($('#tab2'));
    setTab($('#tab3'));

    function setTab(nav) {

        var line = $('<div />').addClass('line');

        line.appendTo(nav);

        var active = nav.find('.active'),
        pos = 0,
        wid = 0;

        if(active.length) {
            pos = active.position().left;
            wid = active.width();
            line.css({
                left: pos,
                width: wid
            });
        }

        nav.find('ul li a').click(function(e) {
            e.preventDefault();
            if(!$(this).parent().hasClass('active')) {

                var _this = $(this);

                nav.find('ul li').removeClass('active');

                var position = _this.parent().position();
                var width = _this.parent().outerWidth();

                if(position.left >= pos) {
                    line.animate({
                        width: ((position.left - pos) + width)
                    }, 300, function() {
                        line.animate({
                            width: width,
                            left: position.left
                        }, 100);
                        _this.parent().addClass('active');
                    });
                } else {
                    line.animate({
                        left: position.left,
                        width: ((pos - position.left) + wid)
                    }, 300, function() {
                        line.animate({
                            width: width
                        }, 100);
                        _this.parent().addClass('active');
                    });
                }

                pos = position.left;
                wid = width;

            }
        });

    }

    $('[data-tooltip]').hover(function(e){
        var title = $(this).data('tooltip');
        $('<div/>').addClass('tooltip').text(title).appendTo('body').fadeIn(150);
    }, function() {
        $('.tooltip').fadeOut(150, function(e) {
            $('.tooltip').remove();
        });
    }).mousemove(function(e) {
        var mousex = e.pageX + 12,
        mousey = e.pageY + 8;
        $('.tooltip').css({
            top: mousey,
            left: mousex
        });
    });

});
