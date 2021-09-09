
module.exports = {
    devServer: {
        hot: true,
        port: process.env.VUE_APP_WEB_PORT
    },

    configureWebpack: {
        resolve: {
            alias: {
                "@": path.resolve(__dirname, 'src/')
            }
        }
    }
}
