import { dotGet } from './helpers'

export default {
    functional: true,
    props: {
        item: {
            type: Object,
            required: true
        },
        column: {
            type: Object,
            required: true
        }
    },
    render(h, { props, data, listeners }) {
        if(props.column.field) {
            let value = dotGet(props.item, props.column.field)
            if(typeof value !== 'string') {
                value = JSON.stringify(value)
            }
            if(props.column.scopedSlots && typeof props.column.scopedSlots.default === 'function') {
                return h('div', data, props.column.scopedSlots.default({ value, item: props.item, column: props.column }))
            }
            return h('div', data, value)
        }
        if(props.column.scopedSlots && typeof props.column.scopedSlots.default === 'function') {
            return h('div', data, props.column.scopedSlots.default(props))
        }
        return h('div', data, props.column.children)
    }
}
