<template>
    <div v-bind:class="{ messages: true, active: messages.length, multiple: messages.length > 1, open: open }" ref="messageList">
        <a @click.prevent="toggle()" href="">
            <vector src="../img/bell.svg"></vector>
        </a>
        <div v-bind:class="{ list: true, open: open, showAll: showAll }">
            <ul v-if="messages.length">
                <li v-for="(item, index) in listMessages" :class="item.type">
                    <div class="icon" v-html="icons[item.type]"></div>
                    {{ item.message | lang }}
                    <a @click.prevent="remove(item.index)" href="" v-html="icons['close']"></a>
                </li>
            </ul>
            <div v-bind:class="{ controls: true, show: messages.length > 1 }">
                <label class="switch inline">
                    <input type="checkbox" v-model="showAll">
                    <div></div>
                </label>
                <div class="remove" @click="remove(-1)">
                    <vector src="../img/trash.svg"></vector>
                </div>
            </div>
            <div v-if="messages.length < 1" class="noMessages">
                {{ 'No messages' | lang }}
            </div>
        </div>
    </div>
</template>

<script>
module.exports = {
    data() {
        return {
            messages: [],
            open: false,
            icons: {
                success: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper fill" transform="translate(0.5, 0.5)"><polyline data-color="color-2" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" points=" 6,12 10,16 18,8 " stroke-linejoin="miter"></polyline> <circle fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" cx="12" cy="12" r="11" stroke-linejoin="miter"></circle></g></svg>',
                error: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper fill" transform="translate(0.5, 0.5)"><line data-color="color-2" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="16" y1="8" x2="8" y2="16" stroke-linejoin="miter"></line> <line data-color="color-2" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="16" y1="16" x2="8" y2="8" stroke-linejoin="miter"></line> <circle fill="none" stroke="#000000" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" cx="12" cy="12" r="11" stroke-linejoin="miter"></circle></g></svg>',
                warning: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper fill" transform="translate(0.5, 0.5)"><circle fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" cx="12" cy="12" r="11" stroke-linejoin="miter"></circle><line data-color="color-2" fill="none" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" x1="12" y1="7" x2="12" y2="13" stroke-linejoin="miter"></line><circle data-color="color-2" class="fill" data-stroke="none" cx="12" cy="17" r="1" stroke-linejoin="miter" stroke-linecap="square"></circle></g></svg>',
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
                if(response.data.length > self.messages.length) {
                    self.open = true;
                }
                self.messages = response.data;
            }).catch(function(error) {
                self.open = true;
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

<style lang="scss">
.messages {
    left: 50%;
    top: 50%;
    position: absolute;
    height: 44px;
    @include flexbox;
    @include translate(-50%, -50%);
    @include transition;
    & > a {
        display: block;
        width: 44px;
        position: relative;
        height: 44px;
        border-radius: 6px;
        @include transition;
        svg {
            width: 14px;
            height: 14px;
            display: block;
            position: absolute;
            left: 50%;
            top: 50%;
            @include transition;
            @include translate(-50%, -50%);
            .fill {
                fill: $textColor;
            }
        }
        &:hover {
            background: $light;
        }
    }
    &.multiple {
        margin-left: -41px;
    }
    .list {
        width: 0;
        opacity: 0;
        visibility: hidden;
        position: relative;
        @include transition(opacity .2s ease 0s, visibility 0s ease .8s, width .5s ease .1s);
        &.open {
            width: 160px;
            opacity: 1;
            visibility: visible;
            @include transition(opacity .5s ease .3s, visibility 0s ease 0s, width .5s ease 0s);
        }
        .noMessages {
            line-height: 44px;
            text-align: center;
            color: $textColorLight;
        }
        .controls {
            position: absolute;
            left: 100%;
            top: 0;
            height: 44px;
            opacity: 0;
            visibility: hidden;
            @include transition;
            @include flexbox;
            @include align-items(center);
            .switch {
                display: block;
                margin: 0 8px 0 12px;
                height: 20px;
            }
            .remove {
                display: block;
                width: 22px;
                cursor: pointer;
                height: 22px;
                position: relative;
                opacity: .4;
                @include transition;
                svg {
                    width: 14px;
                    height: 14px;
                    display: block;
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    @include translate(-50%, -50%);
                    .fill {
                        fill: $textColor;
                    }
                }
                &:hover {
                    opacity: 1;
                }
            }
            &.show {
                opacity: 1;
                visibility: visible;
            }
        }
        ul {
            margin: 0;
            padding: 0;
            list-style: none;
            position: absolute;
            top: -1px;
            left: 8px;
            right: 0;
            border-radius: 6px;
            border: 1px solid transparent;
            @include transition;
            li {
                position: relative;
                margin: 0 0 8px 0;
                padding: 9px 32px 9px 40px;
                border-radius: 6px;
                color: #fff;
                font-size: 14px;
                text-shadow: 0 1px 1px rgba(#000, .12);
                &:last-child {
                    margin-bottom: 0;
                }
                .icon {
                    position: absolute;
                    left: 12px;
                    top: 50%;
                    @include translate(0, -50%);
                }
                a {
                    display: block;
                    width: 20px;
                    height: 20px;
                    position: absolute;
                    right: 8px;
                    top: 50%;
                    opacity: .7;
                    @include transition;
                    @include translate(0, -50%);
                    &:hover {
                        opacity: 1;
                    }
                    svg {
                        position: absolute;
                        left: 50%;
                        top: 50%;
                        width: 20px;
                        height: 20px;
                        @include translate(-50%, -50%);
                        * {
                            stroke: #fff;
                        }
                        &.fill {
                            stroke: none;
                            fill: #fff;
                        }
                    }
                }
                svg {
                    width: 16px;
                    height: 16px;
                    display: block;
                    * {
                        stroke: #fff;
                    }
                    &.fill {
                        stroke: none;
                        fill: #fff;
                    }
                }
                &.success {
                    border-color: $success;
                    background: fade-out($success, .2);
                }
                &.error {
                    border-color: $error;
                    background: fade-out($error, .2);
                }
                &.warning {
                    border-color: $warning;
                    background: fade-out($warning, .2);
                }
            }
        }
        &.showAll {
            ul {
                background: #fff;
                padding: 12px;
                border-color: $border;
            }
        }
    }
    &.active {
        & > a {
            svg {
                opacity: 1;
            }
            &:before {
                content: '';
                z-index: 1;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: $error;
                position: absolute;
                top: -2px;
                right: -2px;
                border: 2px solid $snow;
            }
        }
        .list {
            &.open {
                width: 320px;
            }
        }
    }
}
</style>
