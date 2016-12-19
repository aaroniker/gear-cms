jQuery(document).ready(function($) {

    $(document).on("click", "#expand", function(e) {
        $("#overlay").fadeToggle(400, function() {
            $("#left").toggleClass("active");
            $("#expand").toggleClass("active");
        });
    });

    $(document).on("click", "#switch", function(e) {

        var _this = $(this);
        var _active = 1;

        if(_this.hasClass("small")) {
            _active = 0;
        }

        $.ajax({
            method: "POST",
            url: url + "admin/",
            data: {
                method: "setMenuSmall",
                active: _active
            },
            success: function() {

                _this.toggleClass("small");
                $("#left").toggleClass("small");
                $("#head").toggleClass("small");
                $("main").toggleClass("small");

                if(!_this.hasClass("small")) {
                    $("#left .drop ul").slideDown(300);
                } else {
                    $("#left .drop ul").hide();
                }

                setTimeout(function() {
                    window.dispatchEvent(new Event('resize'));
                }, 300);

            }
        });

    });

    $(document).on("click", "#nav > ul > li > .arrow", function(e) {

        var _this = $(this).parent();
        var _url = $(this).data("id");
        var _active = 0;

        if(!_this.children("ul").is(":visible")) {
            _active = 1;
        }

        _this.toggleClass("drop");

        _this.children("ul").slideToggle(150, function() {
            _this.toggleClass("dropFade");
            $.ajax({
                method: "POST",
                url: url + "admin/",
                data: {
                    method: "setMenu",
                    url: _url,
                    active: _active
                }
            });
        });
    });

    $(document).on("click", "a.ajaxCall", function(e) {

        e.preventDefault();

        var _this = $(this);

        $.ajax({
            method: "POST",
            url: _this.attr("href"),
            success: function() {
                $.event.trigger({
                    type: "fetch"
                });
            }
        });

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

    var focus;

    $(window).focus(function() {
        focus = true;
    }).blur(function() {
        focus = false;
    });

    setInterval("getMessages(url, focus)", 1000);

    setTabs();

});

function setTabs() {

    $(".tabs > section > div").hide();
    $(".tabs > nav > ul > li").removeClass("active");

    $(".tabs > section > div:first-child").show();
    $(".tabs > nav > ul > li:first-child").addClass("active");

    function setTabActive(target) {

        var _this = $(".tabs > nav > ul > li > a[href='" + target + "']");

        if(_this.length) {

            var tabEl = _this.parent().parent().parent().parent();
            var sectionEl = tabEl.children("section");

            window.location.hash = target;

            _this.parent().parent().children("li").removeClass("active");
            sectionEl.children('div').hide();

            _this.parent().addClass("active");
            sectionEl.find(target).show();

        }

    }

    if(window.location.hash) {
        setTabActive(window.location.hash);
    }

    $(document).on("click", ".tabs > nav > ul > li > a", function(e) {
        e.preventDefault();
        setTabActive($(this).attr("href"));
    });

}

function setMessage(message, type) {
    $.ajax({
        type: "POST",
        url: url + "admin/",
        data: {
            method: "setMessage",
            message: message,
            type: type
        }
    });
}

function getMessages(url, focus) {

    if(focus && document.hasFocus()) {
        $.ajax({
            type: "POST",
            url: url + "admin/",
            data: {
                method: "getMessages"
            },
            dataType: "json",
            success: function(message) {
                if(!jQuery.isEmptyObject(message)) {
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
                            }, 2200);

                        }
                    });
                }
            }
        });
    }

}
