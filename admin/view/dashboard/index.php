<section id="dashboard">

    <header>

        <h2><?=lang::get('overview'); ?></h2>

    </header>

    <div class="columns">

        <div class="lg-8 sm-7">
            <div class="box primary">
                <h3><?=lang::get('statistics'); ?></h3>
                <div class="statistics"></div>
            </div>
        </div>

        <div class="lg-4 sm-5">
            <div class="box">
                <h3><?=lang::get('logs'); ?></h3>
                <ul class="logs">
                    <?php
                        $logs = log::getAll();
                        if($logs) {
                            foreach($logs as $log) {
                                echo '
                                    <li>
                                        <span>'.$log->log_entry_type.'('.$log->log_entry_id.') - '.$log->log_action.'- '.$log->log_user_id.'</span>
                                        <div>'.time_since($log->log_datetime).'</div>
                                    </li>
                                ';
                            }
                        } else {
                            echo '<li>'.lang::get('no_results').'</li>';
                        }
                    ?>
                </ul>
            </div>
        </div>

    </div>

</section>

<?php
theme::addJSCode('
    new Vue({
        el: "#dashboard",
        data: {
        }
    });

    new Chartist.Line(".statistics", {
        labels: ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"],
        series: [
            [102, 85, 50, 60, 80, 95, 75]
        ]
    }, {
        height: 200,
        fullWidth: true,
        showArea: true,
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
