$(document).ready(function() {

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

    $("#head .expand").click(function() {
        toggleNav(!$(this).hasClass('active'));
    });

    $("#overlay").click(function() {
        toggleNav(false);
    });


});

function getMessages(url) {
    $.ajax({
        type: "POST",
        url: url + "admin/",
        data: { method: 'getMessages' },
        success: function(message) {
            if(message) {
                alert(message);
            }
        }
    });
}
