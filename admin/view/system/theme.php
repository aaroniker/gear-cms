theme

<?php
theme::addJSCode('
  new Vue({
    el: "#app",
    data: {
      headline: lang["theme"]
    },
    created: function() {

      var vue = this;

      eventHub.$on("setHeadline", function(data) {
        vue.headline = data.headline;
      });

    }
  });
');
?>
