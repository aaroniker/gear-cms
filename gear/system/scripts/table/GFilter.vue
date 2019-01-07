<template>
    <div class="search">
        <input class="form-field" :value="filter" type="text" :placeholder="'Search' | lang" @input="update($event.target.value)">
        <a v-if="filter" href="" @click.prevent="clear">
            <svg>
                <use xlink:href="#crossUI" />
            </svg>
        </a>
    </div>
</template>
<script>
export default {
    name: 'GFilter',
    props: {
        filter: {
            type: String,
            required: true
        }
    },
    methods: {
        update(filter) {
            this.$emit('update:filter', filter)
        },
        clear() {
            this.$emit('update:filter', '')
        }
    }
}
</script>
<style lang="scss">
:root {
    --search-cross: var(--text-muted);
    --search-cross-hover: var(--text);
}
.search {
    position: relative;
    a {
        position: absolute;
        right: 16px;
        top: 50%;
        margin: -8px 0 0 0;
        display: block;
        svg {
            width: 16px;
            height: 16px;
            display: block;
            color: var(--search-cross);
            transition: color .3s ease;
        }
        &:hover {
            svg {
                color: var(--search-cross-hover);
            }
        }
    }
}
</style>
