<section id="user">

    <header>

        <h2><?=lang::get('user'); ?></h2>

        <div class="search">
            <input type="text" v-model="searchString">
        </div>

        <div class="clear"></div>

    </header>

    <data-table :data="tableData" :columns="tableColumns" :filter-key="searchString"></data-table>

</section>
