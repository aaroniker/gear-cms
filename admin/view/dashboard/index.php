<section id="dashboard">

    <div class="columns">

        <div class="lg-8 sm-7">
            <div class="box">
                <h3><?=lang::get('statistics'); ?></h3>
                <div class="statistics"></div>
            </div>
        </div>

        <div class="lg-4 sm-5">
            <div class="box">
                <div id="calendar">
                    <div class="header">
                        <i class="prev icon icon-chevron-left"></i>
                        <h3></h3>
                        <i class="next icon icon-chevron-right"></i>
                    </div>
                    <div class="weekdays"></div>
                    <div class="content"></div>
                </div>
            </div>
        </div>

        <div class="lg-6 md-5">
            <div id="logs" class="box">
                <h3><?=lang::get('logs'); ?></h3>
                <ul v-if="logShowAll">
                    <?php
                        $logs = log::getAll(5);
                        if($logs) {
                            foreach($logs as $log) {
                                $icon = ($log->log_action == 'edit') ? '<i class="icon icon-edit"></i>' : '<i class="icon icon-plus"></i>';
                                $user = new UserModel($log->log_user_id);
                                echo '
                                    <li>
                                        <span data-tooltip="'.$log->log_entry_id.'">'.$icon.' <a href="">'.lang::get($log->log_entry_type).'</a></span>
                                        <div class="user" data-tooltip="'.$user->username.'"><i class="icon icon-person"></i></div>
                                        <div>'.time_since($log->log_datetime).'</div>
                                    </li>
                                ';
                            }
                        } else {
                            echo '<li>'.lang::get('no_results').'</li>';
                        }
                    ?>
                </ul>
                <ul v-if="logShowAdd">
                    <?php
                        $logs = log::getAll(5, 'add');
                        if($logs) {
                            foreach($logs as $log) {
                                $icon = ($log->log_action == 'edit') ? '<i class="icon icon-edit"></i>' : '<i class="icon icon-plus"></i>';
                                $user = new UserModel($log->log_user_id);
                                echo '
                                    <li>
                                        <span data-tooltip="'.$log->log_entry_id.'">'.$icon.' <a href="">'.lang::get($log->log_entry_type).'</a></span>
                                        <div class="user" data-tooltip="'.$user->username.'"><i class="icon icon-person"></i></div>
                                        <div>'.time_since($log->log_datetime).'</div>
                                    </li>
                                ';
                            }
                        } else {
                            echo '<li>'.lang::get('no_results').'</li>';
                        }
                    ?>
                </ul>
                <ul v-if="logShowEdit">
                    <?php
                        $logs = log::getAll(5, 'edit');
                        if($logs) {
                            foreach($logs as $log) {
                                $icon = ($log->log_action == 'edit') ? '<i class="icon icon-edit"></i>' : '<i class="icon icon-plus"></i>';
                                $user = new UserModel($log->log_user_id);
                                echo '
                                    <li>
                                        <span data-tooltip="'.$log->log_entry_id.'">'.$icon.' <a href="">'.lang::get($log->log_entry_type).'</a></span>
                                        <div class="user" data-tooltip="'.$user->username.'"><i class="icon icon-person"></i></div>
                                        <div>'.time_since($log->log_datetime).'</div>
                                    </li>
                                ';
                            }
                        } else {
                            echo '<li>'.lang::get('no_results').'</li>';
                        }
                    ?>
                </ul>
                <nav>
                    <ul>
                        <li :class="{ active: logShowAll }">
                            <a @click="setLogShow('all')">
                                <?=lang::get('all'); ?>
                            </a>
                        </li>
                        <li :class="{ active: logShowAdd }">
                            <a @click="setLogShow('add')">
                                <i class="icon icon-plus"></i>
                            </a>
                        </li>
                        <li :class="{ active: logShowEdit }">
                            <a @click="setLogShow('edit')">
                                <i class="icon icon-edit"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="lg-6 md-7">
            <div class="box action">
                <h3><?=lang::get('actions'); ?></h3>
                <div class="columns">
                    <div class="sm-6">
                        <a href="<?=url::admin('content'); ?>">
                            <svg class="nc-icon outline" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32"><g transform="translate(0, 0)">
                            <line class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="16" y1="15" x2="16" y2="23" stroke-linejoin="miter"></line>
                            <line class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="20" y1="19" x2="12" y2="19" stroke-linejoin="miter"></line>
                            <rect x="1" y="3" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" width="30" height="26" stroke-linejoin="miter"></rect>
                            <line fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="1" y1="8" x2="31" y2="8" stroke-linejoin="miter"></line>
                            <line fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="7" y1="3" x2="7" y2="8" stroke-linejoin="miter"></line>
                            </g></svg>
                            <?=lang::get('manage_pages'); ?>
                        </a>
                    </div>
                    <div class="sm-6">
                        <a href="<?=url::admin('system'); ?>">
                            <svg class="nc-icon outline" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32"><g transform="translate(0, 0)">
                            <line fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="7" y1="3" x2="7" y2="8" stroke-linejoin="miter"></line>
                            <polyline data-cap="butt" fill="none" stroke-width="2" stroke-miterlimit="10" points="31,11 31,29 1,29 1,3
                            	26.003,3 " stroke-linejoin="miter" stroke-linecap="butt"></polyline>
                            <line data-cap="butt" fill="none" stroke-width="2" stroke-miterlimit="10" x1="1" y1="8" x2="21.003" y2="8" stroke-linejoin="miter" stroke-linecap="butt"></line>
                            <path class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M22.003,13
                            	l-3-3l8.379-8.379c0.828-0.828,2.172-0.828,3,0l0,0c0.828,0.828,0.828,2.172,0,3L22.003,13z" stroke-linejoin="miter"></path>
                            <path class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="
                            	M13.91,13.874c1.165-1.165,3.054-1.165,4.22,0s1.165,3.055,0,4.22S13.003,19,13.003,19S12.894,14.89,13.91,13.874z" stroke-linejoin="miter"></path>
                            </g></svg>
                            <?=lang::get('manage_themes'); ?>
                        </a>
                    </div>
                    <div class="sm-6">
                        <a href="<?=url::admin('system'); ?>">
                            <svg class="nc-icon outline" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32"><g transform="translate(0, 0)">
                            <line fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="17" y1="5" x2="31" y2="5" stroke-linejoin="miter"></line>
                            <line fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="1" y1="5" x2="5" y2="5" stroke-linejoin="miter"></line>
                            <line class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="28" y1="16" x2="31" y2="16" stroke-linejoin="miter"></line>
                            <line class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="1" y1="16" x2="16" y2="16" stroke-linejoin="miter"></line>
                            <circle fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" cx="9" cy="5" r="4" stroke-linejoin="miter"></circle>
                            <line fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="17" y1="27" x2="31" y2="27" stroke-linejoin="miter"></line>
                            <line fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="1" y1="27" x2="5" y2="27" stroke-linejoin="miter"></line>
                            <circle fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" cx="9" cy="27" r="4" stroke-linejoin="miter"></circle>
                            <circle class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" cx="20" cy="16" r="4" stroke-linejoin="miter"></circle>
                            </g></svg>
                            <?=lang::get('settings'); ?>
                        </a>
                    </div>
                    <div class="sm-6">
                        <a href="<?=url::admin('extensions'); ?>">
                            <svg class="nc-icon outline" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32"><g transform="translate(0, 0)">
                            <rect x="3" y="9" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" width="26" height="22" stroke-linejoin="miter"></rect>
                            <line class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="7" y1="5" x2="25" y2="5" stroke-linejoin="miter"></line>
                            <line class="primary" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="11" y1="1" x2="21" y2="1" stroke-linejoin="miter"></line>
                            </g></svg>
                            <?=lang::get('manage_plugins'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#dashboard",
        data: {
            logShowAll: true,
            logShowAdd: false,
            logShowEdit: false
        },
        mounted: function() {

            $(document).ready(function(e) {

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
                            contentEl.append("<div class=\'blank\'></div>");
                            r++;
                        }
                    }
                    for(var c = 0; c < 42 - r; c++) {
                        if(c >= e.length) {
                            contentEl.append("<div class=\'blank\'></div>");
                        } else {
                            var v = e[c].day;
                            var m = g(new Date(year, month - 1, v)) ? "<div class=\'today\'>" : "<div>";
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
                var toolbarEl = calendarEl.children(".header");
                var weekdaysEl = calendarEl.children(".weekdays");
                var contentEl = calendarEl.children(".content");

                b();
                days();

                toolbarEl.find(".icon").click(function() {
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

        },
        methods: {
            setLogShow: function(action) {
                if(action == "all") {
                    this.logShowAll = true;
                    this.logShowAdd = false;
                    this.logShowEdit = false;
                } else if(action == "add") {
                    this.logShowAll = false;
                    this.logShowAdd = true;
                    this.logShowEdit = false;
                } else if(action == "edit") {
                    this.logShowAll = false;
                    this.logShowAdd = false;
                    this.logShowEdit = true;
                }
            }
        }
    });

    new Chartist.Line(".statistics", {
        labels: ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"],
        series: [
            [102, 85, 50, 60, 80, 95, 75]
        ]
    }, {
        height: 237,
        fullWidth: true,
        showArea: true,
        low: 0,
        axisY: {
            showGrid: false,
            showLabel: false,
            offset: 0
        },
        axisX: {
            showGrid: false,
            offset: 25,
            labelOffset: {
                x: -7,
                y: 6
            }
        },
        chartPadding: {
            top: 0,
            right: 8,
            bottom: 0,
            left: 8
        },
        plugins: [
            Chartist.plugins.tooltip({
                appendToBody: true
            })
        ]
    });
');
?>
