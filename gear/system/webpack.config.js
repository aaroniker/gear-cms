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
                "axios$": assets + "/axios/dist/axios.min.js",
                "jquery$": assets + "/jquery/dist/jquery.min.js",
                "visibilityjs$": assets + "/visibilityjs/lib/visibility.timers.js"
            }
        }
    }
];
