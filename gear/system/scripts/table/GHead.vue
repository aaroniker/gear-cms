<template>
    <div class="head row">
        <div v-for="(column, index) in columns" :key="column.field + column.label" :class="[{
            'custom': column.scopedSlots && column.scopedSlots.header,
            'sortable': column.sortable,
            'active': isActive(column)
        }, column.staticClass, column.dynamicClass]" v-bind="column.attrs" scope="col" @click.prevent="updateSort(column.field, column.sortable)">
            <g-head-content :column="column" :active="isActive(column)" :sort-desc="sortDesc" />
        </div>
    </div>
</template>
<script>
import GHeadContent from './GHeadContent'
export default {
    name: 'GHead',
    components: {
        GHeadContent
    },
    props: {
        columns: {
            type: Array,
            required: true
        },
        sortBy: {
            type: String,
            default: ''
        },
        sortDesc: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        isActive(column) {
            return !!(column.sortable) && this.isSortedBy(column.field)
        },
        isSortedBy(field) {
            return this.sortBy === field
        },
        updateSort(field, sortable) {
            if(!field || !sortable) {
                return
            }
            if(this.isSortedBy(field)) {
                this.$emit('update:sortDesc', !this.sortDesc)
                return
            }
            this.$emit('update:sortBy', field)
        }
    }
}
</script>
<style lang="scss">
</style>
