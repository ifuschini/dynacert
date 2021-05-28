module.exports = {
    lintOnSave: false,
    runtimeCompiler: true,
    productionSourceMap: false,
    publicPath: "./",
    devServer: {
        open: process.platform === 'darwin',
        host: '0.0.0.0',
        port: 8085, // CHANGE YOUR PORT HERE!
        https: false,
        hotOnly: false,
    },
    configureWebpack: {
        optimization: {
            splitChunks: {
                minSize: 10000,
                maxSize: 240000,
            }
        }
    }
};
