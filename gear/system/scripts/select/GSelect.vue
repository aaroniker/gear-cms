<template>
    <div ref="dropdown" v-bind:class="{ open: open, dropdown: true, select: true, placeholder: !value }">
        <a v-bind:class="{ 'form-select': true, focus: open }" @click.prevent="open = !open">
            {{ translation(displayValue) }}<span class="caret"></span>
        </a>
        <ul>
            <li v-for="(entry, key) in list">
                <a v-text="translation(entry)" @click.prevent="select(key)"></a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'GSelect',
    props: {
        list: {
            type: Object,
            required: true
        },
        value: {
            type: String|Number,
            default: ''
        },
        placeholder: {
            type: String,
            default: ''
        },
        translate: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            open: false,
            val: ''
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
            var el = this.$refs.dropdown;
            var target = e.target;
            if((el !== target) && !el.contains(target)) {
                this.open = false;
            }
        },
        translation(name) {
            return (this.translate) ? this.$lang(name) : name;
        },
        select(key) {
            this.open = false;
            this.val = key;
            this.$emit('input', key);
        }
    },
    computed: {
        displayValue() {
            var value = (this.val) ? this.val : this.value;
            return (value) ? this.list[value] : this.placeholder;
        }
    }
}
</script>
<style lang="scss">
.dropdown {
    &.select {
        width: 100%;
        & > a {
            display: block;
            cursor: pointer;
        }
        &.placeholder {
            & > a {
                color: var(--input-placeholder);
            }
        }
        ul {
            width: 100%;
            li {
                cursor: pointer;
            }
        }
    }
}
</style>
