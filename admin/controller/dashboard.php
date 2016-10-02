<?php

class dashboardController extends controller {

    public function index() {

        theme::addCSS('https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css');
        theme::addJS('https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js');
        theme::addJS('https://cdn.jsdelivr.net/momentjs/2.15.0/moment.min.js');
        theme::addJS('https://cdn.jsdelivr.net/fullcalendar/2.0.1/fullcalendar.min.js');
        theme::addJS('https://cdn.jsdelivr.net/fullcalendar/2.0.1/lang/de.js');
        theme::addJS(url::assets('js/tooltip.js'));
        theme::addJS(url::assets('js/pointlabel.js'));

        include(dir::view('dashboard/index.php'));

    }

}

?>
