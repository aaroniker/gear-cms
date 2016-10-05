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
        for(var e = 0; e < 7; e++) {
            weekdaysEl.append("<div>" + lang[weekdayArr[e]].substring(0, 2) + "</div>");
        }
    }

    function v(e, year) {
        return (new Date(e, year, 0)).getDate();
    }

    function m(e, year, month) {
        return (new Date(e, year - 1, month)).getDay();
    }

    function g(e) {
        return y(new Date) == y(e);
    }

    function y(e) {
        return e.getFullYear() + "/" + (e.getMonth() + 1) + "/" + e.getDate();
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
        var set = function(dir) {
            month = (dir == "next") ? month + 1 : month - 1;
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
