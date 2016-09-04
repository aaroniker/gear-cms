$(document).ready(function() {

    $("#head .expand").click(function() {
        toggleNav(!$(this).hasClass("active"));
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

	$("#upload").gearUpload({
		url: url + "admin/content/media/upload",
		eventBeforeUpload: function(id){
			updateFile(id, "info", lang["uploading"]);
		},
        eventNewFile: function(id, file){
			addFile(id, file);
		},
		eventUploadProgress: function(id, percent){
			var percentStr = percent + "%";
			updateProgress(id, percentStr);
		},
		eventUploadSuccess: function(id, data){
			updateFile(id, "success", lang["complete"]);
			updateProgress(id, "100%");
		},
		eventUploadError: function(id, message){
			updateFile(id, "error", message);
		}
	});

    getMessages(url);

    setInterval("getMessages(url)", 1000);

});

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
                        index: message.key
                    },
                    success: function() {

                        var element = $(message.html).appendTo("#messages");

                        element.addClass("animated fadeInDown");

                        setTimeout(function() {
                            element.removeClass("fadeInDown").addClass("fadeOutUp");
                            element.one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function() {
                                element.remove();
                            });
                        }, 3000);

                    }
                });
            }
        }
    });
}

function addFile(id, file) {

    var name = file.name;
    var size = file.size;

    var template = "<li id='file" + id + "'><p><h4>" + name + " (" + size + ")</h4><small>" + lang["status"] + ": <strong>" + lang["waiting"] + "</strong></small></p><div class='progress'><div></div></div></li>";

    $("#upload").find("ul").prepend(template);

}

function updateFile(id, status, message) {
    $("#file" + id).find("strong").html(message).addClass(status);
}

function updateProgress(id, percent) {
    $("#file" + id).find(".progress").children("div").width(percent);
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
