jQuery(document).ready(function($) {

    $("#head .expand").click(function() {
        toggleNav(!$(this).hasClass("active"));
    });

    $("#nav .close").click(function() {
        toggleNav(false);
    });

    $(document).on("click", ".delete", function(e) {

        e.preventDefault();

        var _this = $(this);

        if(!_this.hasClass("active")) {

            _this.addClass("active");

            var div = $("<div><i class='icon confirm icon-ios-checkmark-outline'></i><i class='icon abort icon-ios-close-empty'></i></div>");
            var element = $(this).append(div);

            div.addClass("animated zoomIn");

            div.on("click", ".abort", function() {
                div.fadeOut(300, function() {
                    div.remove();
                    _this.removeClass("active");
                });
            });

            div.on("click", ".confirm", function() {
                if(_this.hasClass("ajax")) {
                    $.ajax({
                        method: "POST",
                        url: _this.attr("href"),
                        success: function() {
                            div.fadeOut(300, function() {
                                div.remove();
                                _this.removeClass("active");
                            });
                            $.event.trigger({
                                type: "fetch"
                            });
                        }
                    });
                } else {
                    window.location = _this.attr("href");
                }
            });

        }

    });

    getMessages(url);

    setInterval("getMessages(url)", 1200);

    setTabs();

});

function setTabs() {

    if(window.location.hash) {

        var target = window.location.hash;

        $(".tabs > nav > ul > li > a[href='" + target + "']").parent().addClass('active');

        $(".tabs > section > div").hide();
        $(".tabs > section").find(target).show();

    } else {
        $(".tabs > section > div").hide();
        $(".tabs > section > div:first-child").show();
        $(".tabs > nav > ul > li:first-child").addClass("active");
    }

    $(".tabs > nav > ul > li > a").click(function(e) {

        e.preventDefault();

        var target = $(this).attr("href");

        window.location.hash = target;

        $(".tabs > nav > ul > li").removeClass("active");
        $(".tabs > section > div").hide();

        $(this).parent().addClass("active");
        $(".tabs > section").find(target).show();

    });

}

function getMessages(url) {
    $.ajax({
        type: "POST",
        url: url + "admin/",
        data: {
            method: "getMessages"
        },
        dataType: "json",
        success: function(message) {
            if(message) {
                $.ajax({
                    type: "POST",
                    url: url + "admin/",
                    data: {
                        method: "deleteMessage",
                        index: message.index
                    },
                    success: function() {

                        var element = $(message.html).appendTo("#messages");

                        element.addClass("animated fadeInDown");

                        setTimeout(function() {
                            element.removeClass("fadeInDown").addClass("fadeOutUp");
                            element.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
                                element.remove();
                            });
                        }, 3200);

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
    var active = expand.hasClass("active");

    expand.toggleClass("active");

    if(open) {
        $("body").addClass("fix");
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
                $("body").removeClass("fix");
            });
        });
    }

}
