<section id="dashboard">

    <header>

        <h2><?=lang::get('overview'); ?></h2>

    </header>

    <div class="columns">

        <div class="lg-7">
            <div class="box">
                <h3><?=lang::get('statistics'); ?></h3>
                <div class="statistics"></div>
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
            [12, 9, 7, 8, 5, 10, 20]
        ]
    }, {
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
                x: 0,
                y: 5
            }
        },
        chartPadding: {
            top: 15,
            right: 15,
            bottom: 0,
            left: 5
        },
        plugins: [
            Chartist.plugins.tooltip({
                appendToBody: true
            })
        ]
    });
');
?>
