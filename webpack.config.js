var _ = require('lodash');
var webpack = require('webpack');
var path = require('path');
var glob = require('glob');
const { VueLoaderPlugin } = require('vue-loader');
var exports = [];

glob.sync('{gear/modules/**,gear/installer/**,gear/system/**,extensions/**,themes/**}/webpack.config.js', {
    ignore: '**/node_modules/*'
}).forEach(function(file) {
    var dir = path.join(__dirname, path.dirname(file));
    exports = exports.concat(require('./' + file).map(function(config) {
        return _.merge({
            context: dir,
            output: {
                path: dir
            },
            module: {
                rules: [
                    {
                        test: /\.vue$/,
                        use: [
                            'vue-loader'
                        ]
                    },
                    {
                        test: /\.css$/,
                        use: [
                            'vue-style-loader',
                            'css-loader'
                        ]
                    },
                    {
                        test: /\.scss/,
                        use: [
                            'vue-style-loader',
                            {
                                loader: 'css-loader'
                            },
                            'sass-loader',
                            {
                                loader: 'sass-resources-loader',
                                options: {
                                    resources: [
                                        path.resolve(__dirname, 'gear/system/modules/theme/styles/preload.scss'),
                                    ]
                                }
                            }
                        ]
                    },
                    {
                        test: /\.svg$/,
                        use: [
                            'svg-inline-loader'
                        ]
                    }
                ]
            },
            plugins: [
                new VueLoaderPlugin()
            ]
        }, config);
    }));
});

module.exports = exports;
