var assets = __dirname + "/../../../assets";

module.exports = [
    {
        entry: {
            "messages": "./scripts/messages.vue"
        },
        output: {
            filename: "./scripts/dist/[name].js"
        }
    }
];
