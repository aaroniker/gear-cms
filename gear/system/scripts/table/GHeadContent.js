const capitalize = str => {
    if(!str) {
        return
    }
    return str.charAt(0).toUpperCase() + str.slice(1)
}

export default {
    functional: true,
    props: {
        column: {
            type: Object,
            required: true
        },
        active: {
            type: Boolean,
            required: true
        },
        sortDesc: {
            type: Boolean,
            required: true
        }
    },
    render(h, { props, parent }) {
        if(props.column.scopedSlots && props.column.scopedSlots.header) {
            return h('span', {
                on: {
                    click(e) {
                        e.stopPropagation()
                    }
                }
            }, props.column.scopedSlots.header(props))
        }
        let children = [h('span', capitalize(parent.$lang(props.column.label || props.column.field)))]
        if(props.column.sortable) {
            children.push(h('span', {
                class: {
                    active: props.active,
                    sortDown: props.sortDesc,
                    sortUp: !props.sortDesc
                }
            }))
        }
        return children
    }
}
