<g-table :items='<?= json_encode($app->user->getUsers()); ?>'>
    <g-column class="grow" field="username" label="Name">
        <template slot-scope="props">
            <a :href="'<?= $app->route->getLink('/users', ['edit']); ?>/' + props.item.id" v-text="props.value"></a>
        </template>
    </g-column>
    <g-column class="half" field="email" label="Email"></g-column>
    <g-column class="action" label="" :sortable="false">
        <template slot-scope="props">
            <g-dropdown :hover="true" :dots="true" label="English" :list="[
                ['<?= $app->route->getLink('/users', ['edit']); ?>/' + props.item.id, 'Edit'],
                ['', 'Delete', 'delete']
            ]"></g-dropdown>
        </template>
    </g-column>
</g-table>
