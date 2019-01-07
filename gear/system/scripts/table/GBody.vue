<template>
    <div class="body">
        <div class="row" v-for="(d, index) in items" :key="index">
            <g-cell v-for="(column, columnIndex) in columns" v-bind="column.attrs" :item="d" :class="column.staticClass" :column="column" :key="columnIndex" />
        </div>
        <div class="row" v-if="items.length < 1">{{ 'No entries' | lang }}</div>
    </div>
</template>
<script>
import GCell from './GCell'
export default {
    name: 'GBody',
    components: {
        GCell
    },
    props: {
        items: {
            type: Array,
            required: true
        },
        columns: {
            type: Array,
            required: true
        }
    }
}
</script>
