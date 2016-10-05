$(function() {
    function days() {
        weekdays();
        var e = h();
        var r = 0;
        var u = false;
        contentEl.empty();
        while(!u) {
            if(weekdayArr[r] == e[0].weekday) {
                u = true;
            } else {
                contentEl.append('<div class="blank"></div>');
                r++;
            }
        }
        for(var c = 0; c < 42 - r; c++) {
            if(c >= e.length) {
                contentEl.append('<div class="blank"></div>');
            } else {
                var v = e[c].day;
                var m = g(new Date(year, month - 1, v)) ? '<div class="today">' : "<div>";
                contentEl.append(m + "" + v + "</div>");
            }
        }
        toolbarEl.find("h3").text(lang[monthArr[month - 1]] + " " + year);
    }

    function h() {
        var e = [];
        for(var r = 1; r < v(year, month) + 1; r++) {
            e.push({
                day: r,
                weekday: weekdayArr[m(year, month, r)]
            });
        }
        return e;
    }

    function weekdays() {
        weekdaysEl.empty();
        for(var i = 0; i < 7; i++) {
            weekdaysEl.append("<div>" + lang[weekdayArr[i]].substring(0, 2) + "</div>");
        }
    }

    function v(year, month) {
        return (new Date(year, month, 0)).getDate();
    }

    function m(year, month, day) {
        return (new Date(year, month - 1, day)).getDay();
    }

    function g(date) {
        return y(new Date) == y(date);
    }

    function y(date) {
        return date.getFullYear() + "/" + (date.getMonth() + 1) + "/" + date.getDate();
    }

    function b() {
        var date = new Date;
        year = date.getFullYear();
        month = date.getMonth() + 1;
    }

    var e = null;
    var year = null;
    var month = null;
    var r = [];

    var monthArr = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
    var weekdayArr = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];

    var calendarEl = $("#calendar");
    var toolbarEl = calendarEl.find(".header");
    var weekdaysEl = calendarEl.find(".weekdays");
    var contentEl = calendarEl.find(".content");

    b();
    days();

    toolbarEl.find('.icon').on("click", function() {
        var set = function(direction) {
            month = (direction == "next") ? month + 1 : month - 1;
            if(month < 1) {
                month = 12;
                year--;
            } else if(month > 12) {
                month = 1;
                year++;
            }
            days();
        };
        if($(this).hasClass("prev")) {
            set("prev");
        } else {
            set("next");
        }
    });

});
