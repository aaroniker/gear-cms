<template>
    <div ref="dropdown" v-bind:class="{ open: open, dropdown: true, hover: hover, dots: dots }">
        <a v-if="!dots" href="" :class="'btn ' + classes" @click.prevent="open = !open">
            {{ translation(label) }}<span class="caret"></span>
        </a>
        <span v-if="dots" @click.prevent="toggle()"><i></i></span>
        <ul>
            <li v-for="entry in list" :class="entry[2]">
                <a :href="entry[0]" v-text="translation(entry[1])"></a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'GDropdown',
    props: {
        list: {
            type: Array,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        classes: {
            type: String,
            default: ''
        },
        translate: {
            type: Boolean,
            default: true
        },
        hover: {
            type: Boolean,
            default: false
        },
        dots: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            open: false
        }
    },
    created() {
        document.addEventListener('click', this.documentClick);
    },
    destroyed () {
        document.removeEventListener('click', this.documentClick);
    },
    methods: {
        documentClick(e) {
            let el = this.$refs.dropdown,
                target = e.target;
            if((el !== target) && !el.contains(target)) {
                this.open = false;
            }
        },
        translation(key) {
            return (this.translate) ? this.$lang(key) : key;
        }
    }
}
</script>
<style lang="scss">
:root {
    --dropdown-dots: var(--light-2);
    --dropdown-dots-hover: var(--light-4);
    --dropdown-dots-active: var(--light-3);
    --dropdown-background: var(--light-6);
    --dropdown-color: var(--text);
    --dropdown-list-color: var(--text);
    --dropdown-list-color-hover: var(--light-2);
    --dropdown-background-hover: var(--light-5);
}
.light {
    --dropdown-background: var(--light-7);
    --dropdown-background-hover: var(--light-6);
    .dropdown {
        ul {
            border: 1px solid var(--light-5);
        }
    }
}
.dropdown {
    position: relative;
    z-index: 1;
    display: table;
    --dropdown-translate-active: 4px;
    ul {
        padding: 0;
        margin: 0;
        list-style: none;
        position: absolute;
        border-radius: 6px;
        top: 100%;
        left: 0;
        min-width: 140px;
        opacity: 0;
        visibility: hidden;
        transform: scaleY(.6) translateY(0);
        transform-origin: 50% 0;
        background: var(--dropdown-background);
        box-shadow: 0 1px 4px var(--shadow);
        transition: opacity .2s ease, visibility .2s ease, transform .3s cubic-bezier(.4, .6, .5, 1.2);
        li {
            opacity: 0;
            transition: opacity .2s ease;
            $i: 1;
            @while $i <= 10 {
                $delay: $i * 60;
                &:nth-child(#{$i}) {
                    transition-delay: #{$delay}ms;
                }
                $i: $i + 1;
            }
            a {
                display: block;
                white-space: nowrap;
                padding: 10px 16px;
                font-size: 14px;
                font-weight: 500;
                color: var(--dropdown-color);
                transition: background .3s ease, color .3s ease;
                &:hover {
                    background: var(--dropdown-background-hover);
                    color: var(--dropdown-list-color-hover);
                }
            }
            &:first-child {
                a {
                    border-radius: 6px 6px 0 0;
                }
            }
            &:last-child {
                a {
                    border-radius: 0 0 6px 6px;
                }
            }
            &.delete {
                a {
                    color: var(--error);
                }
            }
        }
    }
    &.dots {
        --dropdown-translate-active: 0;
        & > span {
            width: 24px;
            height: 22px;
            position: relative;
            cursor: pointer;
            display: block;
            z-index: 1;
            transition: transform .3s ease;
            i {
                display: block;
                position: absolute;
                left: 50%;
                top: 50%;
                margin: -2px 0 0 -2px;
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background: var(--dropdown-dots);
                transition: background .3s ease;
                &:before,
                &:after {
                    content: '';
                    width: 4px;
                    height: 4px;
                    border-radius: 50%;
                    background: inherit;
                    position: absolute;
                    top: 0;
                }
                &:before {
                    left: -6px;
                }
                &:after {
                    left: 6px;
                }
            }
            &:hover {
                i {
                    background: var(--dropdown-dots-hover);
                }
            }
        }
        ul {
            padding: 34px 0 0 0;
            right: -4px;
            top: -6px;
            left: auto;
            min-width: 112px;
            li {
                &:first-child {
                    a {
                        border-radius: 0;
                    }
                }
            }
        }
    }
    &.hover:hover,
    &.open {
        z-index: 2;
        & > span {
            transform: rotate(90deg);
            &:hover i,
            i {
                background: var(--dropdown-dots-active);
            }
        }
        .btn {
            .caret {
                &:before {
                    margin-right: 5px;
                    transform: scale(.92, .8) rotate(-130deg);
                }
                &:after {
                    transform: scale(.92, .8) rotate(130deg);
                }
            }
        }
        ul {
            opacity: 1;
            visibility: visible;
            transform: scaleY(1) translateY(var(--dropdown-translate-active));
            li {
                opacity: 1;
            }
        }
    }
}
</style>
