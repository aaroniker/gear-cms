<section id="user">

	<header>
    
    	<h2>{{ checked | json }} {{ 'user' | lang }}</h2>
    
    </header>

    <table class="table">
        <thead>
            <tr>
                <th>
                	<div class="checkbox">
                		<input id="checkAll" type="checkbox" v-model="checkAll">
                        <label for="checkAll"></label>
                    </div>
               	</th>
                <th>
                    Name
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users">
                <td>
                	<div class="checkbox">
                		<input id="user{{ user.id }}" type="checkbox" v-model="checked" :value="user.id" number>
                        <label for="user{{ user.id }}"></label>
                    </div>
                </td>
                <td>
                    {{ user.email }}
                </td>
            </tr>
        </tbody>
    </table>

</section>