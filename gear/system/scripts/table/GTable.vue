<template>
    <div class="gtable">
        <div class="filter">
            <g-filter :filter.sync="options.filter" />
        </div>
        <div>
            <g-head :columns="columns" :sort-by.sync="options.sortBy" :sort-desc.sync="options.sortDesc" />
            <g-body :columns="columns" :items="actualItems" />
        </div>
        <g-pagination :per-page="perPage" :page.sync="page" :total="total" />
    </div>
</template>
<script>
import { load, defaultProps, dotGet, dotSet } from './helpers'
import GBody from './GBody.vue'
import GHead from './GHead.vue'
import GPagination from './GPagination.vue'
import GFilter from './GFilter.vue'

export default {
    name: 'GTable',
    components: {
        GBody,
        GHead,
        GPagination,
        GFilter
    },
    props: {
        items: {
            type: [Array, Function],
            required: true
        },
        perPage: {
            type: Number,
            default: 10
        },
        sortBy: {
            type: String,
            default: ''
        },
        sortDesc: {
            type: Boolean,
            default: false
        },
        filter: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            actualItems: [],
            vnodes: [],
            total: 0,
            page: 1,
            options: {
                sortBy: this.sortBy,
                sortDesc: this.sortDesc,
                filter: this.filter
            }
        }
    },
    computed: {
        identifier() {
            return `by:${this.sorting.by}|order:${this.sorting.order}|filter:${this.options.filter}|page:${this.page}|per_page:${this.perPage}`
        },
        asynchronous() {
            return this.items instanceof Function
        },
        columns() {
            return this.vnodes.map(vnode => {
                let { componentOptions: { Ctor: { options: { props } }, propsData, children }, data: { scopedSlots, attrs, class: dynamicClass, staticClass } } = vnode
                let { field, label, sortable, filterable, render } = defaultProps(props, propsData)
                return {
                    field,
                    label,
                    sortable,
                    filterable,
                    render,
                    scopedSlots,
                    children,
                    attrs,
                    dynamicClass,
                    staticClass
                }
            })
        },
        filterable() {
            return this.columns.filter(column => {
                return column.filterable
            }).map(column => {
                return column.field
            }).filter(field => field)
        },
        filtering() {
            return {
                query: this.options.filter.toLowerCase(),
                fields: this.filterable
            }
        },
        paging() {
            return {
                page: this.page,
                perPage: this.perPage
            }
        },
        sorting() {
            return {
                by: this.options.sortBy,
                order: !this.options.sortDesc ? 'asc' : 'desc'
            }
        }
    },
    watch: {
        items: 'loadItems',
        identifier: 'loadItems',
        sortBy: {
            immediate: true,
            handler(val) {
                this.$set(this.options, 'sortBy', val)
            }
        },
        sortDesc: {
            immediate: true,
            handler(val) {
                this.$set(this.options, 'sortDesc', val)
            }
        },
        filter: {
            immediate: true,
            handler(val) {
                this.$set(this.options, 'filter', val)
            }
        },
        'options.sortBy'(val) {
            this.$emit('update:sortBy', val)
        },
        'options.sortDesc'(val) {
            this.$emit('update:sortDesc', val)
        },
        'options.filter'(val) {
            this.$emit('update:filter', val)
        }
    },
    created() {
        this.loadSlots()
        this.loadItems()
    },
    methods: {
        loaded(data) {
            let items = JSON.parse(JSON.stringify(data.items))
            this.actualItems = items.map(item => {
                this.columns.filter(column => typeof column.render === 'function').forEach(column => {
                    let parts = column.field.split('.')
                    let originalField = parts.reduce((a, b, index) => {
                        if(index === parts.length - 1) {
                            return `${a}.$_${b}`
                        }
                        return `${a}.${b}`
                    })
                    if(parts.length === 1) {
                        originalField = `$_${originalField}`
                    }
                    dotSet(item, originalField, dotGet(item, column.field))
                    dotSet(item, column.field, column.render(dotGet(item, column.field)))
                })
                return item
            })
            this.total = data.total
            this.$emit('loaded', {
                items: this.actualItems,
                total: data.total
            })
        },
        loadSlots() {
            this.vnodes = !this.$slots.default ? [] : this.$slots.default.filter(vnode => vnode.componentOptions)
        },
        loadItems() {
            this.load(this.items, this.filtering, this.sorting, this.paging)
        },
        load(items, filtering, sorting, paging) {
            if(this.asynchronous) {
                Promise.resolve(items(filtering, sorting, paging)).then(this.loaded)
                return
            }
            if(!items) {
                this.loaded({ items: [], total: 0 })
                return
            }
            this.loaded(load(JSON.parse(JSON.stringify(items)), filtering, sorting, paging))
        }
    }
}
</script>
<style lang="scss">
:root {
    --table-head: var(--light-2);
    --table-row: var(--dark-4);
    --table-row-link: var(--light-4);
    --table-row-link-hover: var(--light-6);
    --table-row-hover: var(--dark-5);
}
.gtable {
    padding: 59px 0 0 0;
    position: relative;
    .filter {
        position: absolute;
        width: 100%;
        max-width: 220px;
        top: 0;
        right: 0;
    }
    .row {
        display: flex;
        flex-direction: row;
        flex-wrap: no-wrap;
        width: 100%;
        line-height: 21px;
        padding: 16px;
        & > div {
            margin-right: 16px;
            &:last-child {
                margin-right: 0;
            }
            &.shrink {
                flex: 0 1 auto;
            }
            &.grow {
                flex: 1 0 auto;
            }
            &.action {
                flex: 0 0 24px;
            }
            &.third {
                flex: 0 0 33%;
            }
            &.half {
                flex: 0 0 50%;
            }
        }
        &:not(.head) {
            background: var(--table-row);
            border-radius: 6px;
            transition: background .3s ease;
            margin: 0 0 8px 0;
            &:hover {
                background: var(--table-row-hover);
            }
            & > div {
                font-size: 14px;
                & > a {
                    font-size: 16px;
                    color: var(--table-row-link);
                    &:hover {
                        color: var(--table-row-link-hover);
                    }
                }
            }
        }
        &.head {
            text-transform: uppercase;
            color: var(--table-head);
            font-size: 12px;
            font-weight: 600;
            padding-top: 0;
            padding-bottom: 8px;
        }
    }
}
</style>
