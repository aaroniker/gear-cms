var _ = require('lodash');
var path = require('path');
var glob = require('glob');
var exports = [];

glob.sync('{gear/modules/**,gear/installer/**,gear/backend/**,themes/**}/webpack.config.js', {ignore: '**/node_modules/*'}).forEach(function(file) {
    var dir = path.join(__dirname, path.dirname(file));
    exports = exports.concat(require('./' + file).map(function(config) {
        return _.merge({context: dir, output: {path: dir}}, config);
    }));
});

module.exports = exports;
