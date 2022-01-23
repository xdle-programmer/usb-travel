const {merge} = require('webpack-merge');
const baseConfig = require('./webpack.config.base');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require('path');

module.exports = merge(baseConfig, {
    mode: 'development',
    watch: true,
});
