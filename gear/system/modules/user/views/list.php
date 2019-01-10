<g-table :items='<?= json_encode($app->user->getUsers()); ?>'>
    <g-column class="grow" field="username" label="Name">
        <template slot-scope="props">
            <a :href="'<?= $app->route->getLink('/users', ['edit']); ?>/' + props.item.id" v-text="props.value"></a>
        </template>
    </g-column>
    <g-column class="half" field="email" label="Email"></g-column>
    <g-column class="action" label="" :sortable="false">
        <template slot-scope="props">
            <div class="dropdown dots">
                <span>
                    <i></i>
                </span>
                <ul>
                    <li><a :href="'<?= $app->route->getLink('/users', ['edit']); ?>/' + props.item.id"><?= __('Edit'); ?></a></li>
                    <li class="delete"><a href=""><?= __('Delete'); ?></a></li>
                </ul>
            </div>
        </template>
    </g-column>
</g-table>
