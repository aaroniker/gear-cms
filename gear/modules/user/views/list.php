<g-table :items='<?= json_encode($app->user->getUsers()); ?>'>
    <g-column class="grow" field="username" label="Name">
        <template slot-scope="props">
            <a href="" v-text="props.value"></a>
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
                    <li><a href="">Edit</a></li>
                    <li class="delete"><a href="">Delete</a></li>
                </ul>
            </div>
        </template>
    </g-column>
</g-table>
