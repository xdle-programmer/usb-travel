const path = require('path');
const fs = require('fs');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

let excludeDirName = '__exclude__';

let getFiles = function (dir, files_, extension) {
    files_ = files_ || [];
    let files = fs.readdirSync(dir);
    let regular = new RegExp('.\\.' + extension + '$');

    for (let i in files) {

        let name = dir + '/' + files[i];
        if (fs.statSync(name).isDirectory() && name.indexOf(excludeDirName) === -1) {
            getFiles(name, files_, extension);
        } else if (regular.test(name)) {
            files_.push(name);
        }
    }

    return files_;
};

// Файлы стилей верстки
let styleMarkupArray = [];
getFiles(path.resolve(__dirname, './wp-content/themes/usb-travel/src/markup'), styleMarkupArray, 'scss');
getFiles(path.resolve(__dirname, './wp-content/themes/usb-travel/src/markup'), styleMarkupArray, 'css');

// Js файлы верстки, включая точку входа
let jsMarkupArray = [path.resolve(__dirname, './wp-content/themes/usb-travel/src/index.js')];
getFiles(path.resolve(__dirname, './wp-content/themes/usb-travel/src/markup'), jsMarkupArray, 'js');

let fullArray = jsMarkupArray.concat(styleMarkupArray);

module.exports = {
    mode: 'development',
    entry: {
        style: fullArray,
    },
    output: {
        filename: 'scripts.js',
        path: path.resolve(__dirname, './wp-content/themes/usb-travel'),
    },

    devtool: "source-map",
    module: {
        rules: [
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: '/node_modules/'
            },
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                ],
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                    },
                    {
                        loader: 'sass-loader',
                    }
                ],
            },
            {
                test: /\.(png|jpg|svg|ttf|eot|woff|woff2)$/,
                type: "asset/resource",
                generator: {
                    filename: (normalModule) => {
                        let filePath = normalModule.module.resourceResolveData.relativePath;
                        filePath = filePath.replace('./wp-content/themes/usb-travel/', '');
                        return filePath;
                    },
                    emit: false,
                },
            },
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].css',
        }),
    ],
};
