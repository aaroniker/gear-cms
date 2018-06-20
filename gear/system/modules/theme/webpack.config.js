var assets = __dirname + "/../../../assets";

module.exports = [
    {
        entry: {
            "theme": "./scripts/theme",
            "messages": "./scripts/messages.vue"
        },
        output: {
            filename: "./scripts/dist/[name].js"
        },
        resolve: {
            alias: {
                "jquery$": assets + "/jquery/dist/jquery.min.js"
            }
        }
    }
];
