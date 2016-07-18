<section id="user">

    <header>

        <h2><?=lang::get('user'); ?></h2>

        <input type="text" v-model="searchString">

    </header>

    <data-table :data="tableData" :columns="tableColumns" :filter-key="searchString"></data-table>

</section>
