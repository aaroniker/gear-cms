export default {
    name: 'GColumn',
    props: {
        label: {
            type: String,
            required: true
        },
        field: {
            type: String,
            default: ''
        },
        sortable: {
            type: Boolean,
            default: true
        },
        filterable: {
            type: Boolean,
            default: true
        },
        render: {
            type: Function
        }
    }
}
