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

                if(_this.hasClass("small")) {

                    _this.removeClass("small");
                    $("#left").removeClass("small");
                    $("#head").removeClass("small");
                    $("main").removeClass("small");

                    $("#left .drop ul").slideDown(300);

                } else {

                    $("#left .drop ul").hide();

                    _this.addClass("small");
                    $("#left").addClass("small");
                    $("#head").addClass("small");
                    $("main").addClass("small");

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

    $(".tabs > section > div").hide();
    $(".tabs > nav > ul > li").removeClass("active");

    $(".tabs > section > div:first-child").show();
    $(".tabs > nav > ul > li:first-child").addClass("active");

    $(document).on("click", ".tabs > nav > ul > li > a", function(e) {

        e.preventDefault();

        var target = $(this).attr("href");
        var tabEl = $(this).parent().parent().parent().parent();
        var sectionEl = tabEl.children("section");

        $(this).parent().parent().children("li").removeClass("active");
        sectionEl.children('div').hide();

        $(this).parent().addClass("active");
        sectionEl.find(target).show();

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

function getMessages(url) {
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
