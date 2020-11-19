const path = require('path');

const config = {
    mode: 'production',
    devtool: 'source-map',
    entry: {
        CourseManager: './es/CourseManager/index.js',
        lesson: './es/lesson.js'
    },
    output: {
        path: path.resolve(__dirname + '/public/js'),
        filename: '[name].js',
    },
    module: {
        rules: [
            {
                use: 'babel-loader',
                test: /\.js$/,
                include: [
                    path.resolve(__dirname, 'es')
                ]
            }
        ]
    }
}

module.exports = config;
