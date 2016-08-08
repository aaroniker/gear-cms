<section id="media">

    <header>

        <h2>{{ headline | lang }}</h2>

        <div class="search">
            <input type="text" v-model="searchString">
        </div>

        <nav>
            <ul>
                <li>
                    <a href="" class="button">
                        <?=lang::get('upload'); ?>
                    </a>
                </li>
            </ul>
        </nav>

    </header>

    <file-table :data="data" :filter-key="searchString"></data-table>

</section>

<?php
    theme::addJSCode('
        new Vue({
            el: "#media",
            data: {
                headline: "media",
                checked: [],
                path: "/",
                data: [],
                tableColumns: ["name", "size"],
                searchString: ""
            },
            events: {
                "checked": function (data) {
                    this.checked = data;
                    if(data.length) {
                        this.headline = data.length + " " + lang["selected"];
                    } else {
                        this.headline = "media";
                    }
                }
            }
        });
    ', true);
?>
