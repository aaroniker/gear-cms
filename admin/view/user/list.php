<section id="user">

    <header>

        <h2><?=lang::get('list'); ?></h2>

        <div class="search">
            <input type="text" v-model="searchString">
        </div>

        <nav>
            <ul>
                <li>
                    <a href="" class="button">
                        <?=lang::get('add'); ?>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="clear"></div>

    </header>

    <data-table :data="tableData" :columns="tableColumns" :filter-key="searchString"></data-table>

</section>
