<g-table :items='<?= json_encode($app->user->getUsers()); ?>'>
    <g-column class="grow" field="username" label="Name">
        <template slot-scope="props">
            <a href="" v-text="props.value"></a>
        </template>
    </g-column>
    <g-column class="half" field="email" label="Email"></g-column>
    <g-column class="action" label="" :sortable="false">
        <template slot-scope="props">
            ...
        </template>
    </g-column>
</g-table>
