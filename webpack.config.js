module.exports = {
    entry: "./application/index.js",
    output: {
        path: __dirname,
        filename: "./assets/bundle.js"
    },
    module: {
        loaders: [
            { 
                test: /\.css$/, loader: "style!css" 
            },
            { 
                test: /\.js$/, 
                exclude: /(node_modules|bower_components)/,
                loader: "babel-loader", 
                options: {
                    presets: ['env'],
                    plugins: [require('babel-plugin-transform-object-rest-spread')]
                }
            }
        ]
    }
}