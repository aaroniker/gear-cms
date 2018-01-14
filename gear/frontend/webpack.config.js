var path = require('path')
var webpack = require('webpack')

module.exports = {
    entry: './src/main.js',
    output: {
        path: path.resolve(__dirname, './assets/js'),
        publicPath: '/assets/js/',
        filename: 'gear.js'
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        sass: [
                            'vue-style-loader',
                            'css-loader',
                            'sass-loader?indentedSyntax'
                        ],
                        scss: [
                            'vue-style-loader',
                            'css-loader',
                            'sass-loader',
                            {
                                loader: 'sass-resources-loader',
                                options: {
                                    resources: [
                                        path.resolve(__dirname, 'assets/scss/framy/vars.scss'),
                                        path.resolve(__dirname, 'assets/scss/framy/mixins/color.scss'),
                                        path.resolve(__dirname, 'assets/scss/framy/mixins/grid.scss'),
                                        path.resolve(__dirname, 'assets/scss/framy/mixins/utils.scss'),
                                        path.resolve(__dirname, 'assets/scss/framy/mixins/responsive.scss')
                                    ]
                                }
                            }
                        ]
                    }
                }
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.(png|jpg|gif|svg)$/,
                loader: 'file-loader',
                options: {
                    useRelativePath: true
                }
            }
        ]
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        }
    },
    devServer: {
        historyApiFallback: true,
        noInfo: true
    },
    performance: {
        hints: false
    },
    node: {
        fs: 'empty'
    },
    devtool: '#eval-source-map'
}

process.noDeprecation = true

if(process.env.NODE_ENV === 'production') {
    module.exports.devtool = '#source-map'
    module.exports.output.publicPath = './assets/js/'
    module.exports.plugins = (module.exports.plugins || []).concat([
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            }
        }),
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
            compress: {
                warnings: false
            }
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true
        })
    ])
}
