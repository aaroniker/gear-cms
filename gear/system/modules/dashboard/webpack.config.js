module.exports = [
    {
        entry: {
            "test": "./scripts/test.vue"
        },
        output: {
            filename: "./scripts/dist/[name].js"
        },
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue-loader" }
            ]
        }
    }
];
