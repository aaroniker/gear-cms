var assets = __dirname + "/../assets";

module.exports = [
    {
        entry: {
            "system": "./scripts/system"
        },
        output: {
            filename: "./scripts/dist/[name].js"
        },
        resolve: {
            alias: {
                "vue-resource$": assets + "/vue-resource/dist/vue-resource.common.js",
            }
        },
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue-loader" }
            ]
        }
    }
];
