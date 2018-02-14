<template>
    <li v-bind:class="{ bell: true, active: messages.length, open: open }" ref="messageList">
        <a @click.prevent="toggle()" href="">
            <vector src="../img/bell.svg"></vector>
        </a>
        <div v-bind:class="{ list: true, open: open }">
            <ul v-if="messages.length">
                <li v-for="(item, index) in listMessages" :class="item.type">
                    <div class="icon" v-html="icons[item.type]"></div>
                    {{ item.message | lang }}
                    <a @click.prevent="remove(item.index)" href="" v-html="icons['close']"></a>
                </li>
                <li v-if="messages.length > 1">
                    <div class="toggleMore" @click="showAll = !showAll">
                        <span v-if="!showAll" v-text="messages.length"></span>
                        {{ (showAll ? 'Show less' : 'Show all') | lang }}
                    </div>
                    <div class="remove" @click="remove(-1)">
                        {{ 'Remove all' | lang }}
                    </div>
                </li>
            </ul>
            <div v-else class="noMessages">
                {{ 'No messages' | lang }}
            </div>
        </div>
    </li>
</template>

<script>
module.exports = {
    data() {
        return {
            messages: [],
            open: false,
            icons: {
                success: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper fill" transform="translate(0.5, 0.5)"><polyline data-color="color-2" fill="none" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" points=" 6,12 10,16 18,8 " stroke-linejoin="miter"></polyline> <circle fill="none" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" cx="12" cy="12" r="11" stroke-linejoin="miter"></circle></g></svg>',
                error: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper fill" transform="translate(0.5, 0.5)"><line data-color="color-2" fill="none" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" x1="16" y1="8" x2="8" y2="16" stroke-linejoin="miter"></line> <line data-color="color-2" fill="none" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" x1="16" y1="16" x2="8" y2="8" stroke-linejoin="miter"></line> <circle fill="none" stroke="#000000" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" cx="12" cy="12" r="11" stroke-linejoin="miter"></circle></g></svg>',
                warning: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper fill" transform="translate(0.5, 0.5)"><circle fill="none" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" cx="12" cy="12" r="11" stroke-linejoin="miter"></circle><line data-color="color-2" fill="none" stroke-width="1" stroke-linecap="square" stroke-miterlimit="10" x1="12" y1="7" x2="12" y2="13" stroke-linejoin="miter"></line><circle data-color="color-2" class="fill" data-stroke="none" cx="12" cy="17" r="1" stroke-linejoin="miter" stroke-linecap="square"></circle></g></svg>',
                close: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" class="fill" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper"><path class="fill" d="M16.7,7.3c-0.4-0.4-1-0.4-1.4,0L12,10.6L8.7,7.3c-0.4-0.4-1-0.4-1.4,0s-0.4,1,0,1.4l3.3,3.3l-3.3,3.3 c-0.4,0.4-0.4,1,0,1.4C7.5,16.9,7.7,17,8,17s0.5-0.1,0.7-0.3l3.3-3.3l3.3,3.3c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3 c0.4-0.4,0.4-1,0-1.4L13.4,12l3.3-3.3C17.1,8.3,17.1,7.7,16.7,7.3z"></path></g></svg>'
            },
            showAll: false
        }
    },
    created() {
        var self = this;
        self.display();
        self.$visibility.every(1000, function() {
            self.display();
        });
        document.addEventListener('click', this.documentClick);
    },
    destroyed () {
        document.removeEventListener('click', this.documentClick);
    },
    methods: {
        display() {
            var self = this;
            self.$api.post('index.php', {
                'method': 'getMessages'
            }).then(function(response) {
                self.messages = response.data;
            }).catch(function(error) {
                self.messages = error;
            });
        },
        remove(index) {
            var self = this;
            self.$api.post('index.php', {
                'method': 'deleteMessage',
                'index': index
            });
        },
        toggle() {
            this.open = !this.open;
        },
        documentClick: function(e) {
            var el = this.$refs.messageList;
            var target = e.target;
            if((el !== target) && !el.contains(target)) {
                this.open = false;
            }
        },
    },
    computed: {
        listMessages() {
            if(!this.showAll) {
                return this.messages.slice().reverse().slice(0, 1);
            }
            return this.messages.slice().reverse();
        }
    }
}
Vue.component('messages', function(resolve) {
    resolve(module.exports);
});
</script>
