<template>
    <div v-bind:class="{ messages: true, active: messages.length, minimal: minimal, open: open }" ref="messageList">
        <a @click.prevent="toggle()" href="">
            <svg>
                <use xlink:href="#bellUI" />
            </svg>
        </a>
        <div v-bind:class="{ list: true, open: open, showAll: (showAll && (messages.length > 1)) }">
            <a v-bind:class="{ expanded: showAll, show: messages.length > 1 }" @click.prevent="showAll = !showAll" href="">
                <svg>
                    <use xlink:href="#expandUI" />
                </svg>
            </a>
            <a v-bind:class="{ show: messages.length > 1 }" @click.prevent="remove(-1)" href="">
                <svg>
                    <use xlink:href="#trashUI" />
                </svg>
            </a>
            <ul v-if="messages.length">
                <li v-for="(item, index) in listMessages" :class="item.type">
                    {{ item.message | lang }}
                    <a @click.prevent="remove(item.index)" href="">
                        <svg>
                            <use xlink:href="#crossUI" />
                        </svg>
                    </a>
                </li>
            </ul>
            <div v-if="messages.length < 1" class="noMessages">
                {{ 'No messages' | lang }}
            </div>
        </div>
    </div>
</template>

<script>
module.exports = {
    props: {
        minimal: {
          type: Boolean,
          default: false
        }
    },
    data() {
        return {
            messages: [],
            open: false,
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
                if(self.messages.length) {
                    self.messages.map(function(item) {
                        setTimeout(function() {
                            if(!item.stay) {
                                self.remove(item.index);
                            }
                        }, window.$gear.messagesTimeout);
                    });
                }
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
            if(!this.showAll && !this.minimal) {
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
    &:not(.minimal) {
        z-index: 9999;
        position: relative;
        display: flex;
        .list {
            opacity: 0;
            visibility: hidden;
            position: absolute;
            display: flex;
            top: -6px;
            right: 36px;
            transition: opacity .3s ease, visibility .3s ease;
            &.open {
                opacity: 1;
                visibility: visible;
            }
            ul {
                li {
                    white-space: nowrap;
                }
            }
        }
    }
    & > a {
        display: table;
        position: relative;
        padding: 4px;
        svg {
            width: 16px;
            height: 16px;
            display: block;
            transition: color .3s ease;
            color: var(--text-muted);
        }
        &:hover {
            svg {
                color: var(--text);
            }
        }
    }
    .list {
        .noMessages {
            line-height: 36px;
            white-space: nowrap;
            color: var(--text-muted);
        }
        & > a {
            display: table;
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s ease, visibility .3s ease;
            padding: 4px;
            margin: 6px 8px 6px 0;
            svg {
                color: var(--text-muted);
                width: 16px;
                height: 16px;
                display: block;
                transition: color .3s ease;
            }
            &:hover {
                svg {
                    color: var(--text);
                }
            }
            &.expanded {
                svg {
                    color: var(--light-5);
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
            border-radius: 6px;
            transition: all .3s ease;
            li {
                position: relative;
                margin: 0 0 8px 0;
                padding: 8px 36px 8px 12px;
                border-radius: 6px;
                color: #fff;
                font-size: 14px;
                line-height: 20px;
                font-weight: 500;
                text-shadow: 0 0 1px rgba(#000, .2);
                &:last-child {
                    margin-bottom: 0;
                }
                a {
                    display: block;
                    position: absolute;
                    right: 8px;
                    top: 8px;
                    padding: 2px;
                    opacity: .75;
                    transition: opacity .3s ease;
                    svg {
                        color: #fff;
                        width: 16px;
                        height: 16px;
                        display: block;
                    }
                    &:hover {
                        opacity: 1;
                    }
                }
                &.success {
                    background: var(--success);
                }
                &.error {
                    background: var(--error);
                }
                &.warning {
                    background: var(--warning);
                }
            }
        }
        &.showAll {
            ul {
                background: var(--dark-5);
                box-shadow: 0 4px 8px -1px var(--dark-3);
                padding: 8px;
            }
        }
    }
    &.active {
        & > a {
            &:before {
                content: '';
                z-index: 1;
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: var(--secondary);
                position: absolute;
                top: 4px;
                right: 5px;
                border: 1px solid var(--dark-3);
            }
            svg {
                color: var(--light-5);
            }
        }
    }
    &.minimal {
        margin-bottom: 20px;
        & > a {
            display: none;
        }
        .list {
            & > a {
                display: none;
            }
            ul {
                li {
                    margin: 0 0 12px 0;
                    &:last-child {
                        margin-bottom: 0;
                    }
                }
            }
            .noMessages {
                display: none;
            }
        }
    }
}
</style>
