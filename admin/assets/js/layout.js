$(document).ready(function() {

    $("#head .expand").click(function() {
        toggleNav(!$(this).hasClass('active'));
    });

    $("#overlay").click(function() {
        toggleNav(false);
    });

    getMessages(url);

    setInterval("getMessages(url)", 800);

});

function getMessages(url) {
    $.ajax({
        type: "POST",
        url: url + "admin/",
        data: {
            method: 'getMessages'
        },
        dataType: "json",
        success: function(message) {
            if(message) {
                $.ajax({
                    type: "POST",
                    url: url + "admin/",
                    data: {
                        method: 'deleteMessage',
                        index: message.key
                    },
                    success: function() {
                        $(message.html).appendTo("#messages").hide().fadeIn(200).delay(2200).fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                });
            }
        }
    });
}

function toggleNav(open) {

    var expand = $(this);
    var overlay = $("#overlay");
    var nav = $("#nav");
    var active = expand.hasClass('active');

    expand.toggleClass('active');

    if(open) {
        $("body").addClass('fix');
        overlay.fadeIn(200, function() {
            nav.animate({
                left: 0
            }, 200);
        });
    } else {
        nav.animate({
            left: -220
        }, 200, function() {
            overlay.fadeOut(200, function() {
                $("body").removeClass('fix');
            });
        });
    }

}
