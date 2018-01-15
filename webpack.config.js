var _ = require('lodash');
var path = require('path');
var glob = require('glob');
var exports = [];

glob.sync('{gear/modules/**,gear/installer/**,gear/backend/**,extensions/**,themes/**}/webpack.config.js', {
    ignore: '**/node_modules/*'
}).forEach(function(file) {
    var dir = path.join(__dirname, path.dirname(file));
    exports = exports.concat(require('./' + file).map(function(config) {
        return _.merge({
            context: dir,
            output: {
                path: dir
            }
        }, config);
    }));
});

if(!exports.length) {
    console.log('No modules with a "webpack.config.js" found.');
    process.exit(1);
}

module.exports = exports;
