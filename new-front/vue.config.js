const path = require('path');

const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CompressionPlugin = require("compression-webpack-plugin");
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const { VueLoaderPlugin } = require('vue-loader');
const { VuetifyLoaderPlugin } = require('vuetify-loader')
const PreloadWebpackPlugin = require('preload-webpack-plugin');

module.exports = {
  devServer: {
    hot: true,
    port: process.env.VUE_APP_WEB_PORT
  },
    chainWebpack: config => {
        config
            .plugin('html')
            .tap(args => {
                args[0].filename = 'index.html'
                return args
            })
        // config.plugins.delete('prefetch')
        //
        // /*
        //    Configure preload to load all chunks
        //    NOTE: use `allChunks` instead of `all` (deprecated)
        // */
        // config.plugin('preload').tap((options) => {
        //     options[0].include = 'allChunks'
        //     return options
        // })
    },

  configureWebpack: {
    resolve: {
      alias: {
        "@": path.resolve(__dirname, 'src/')
      }
    },
      plugins: [
          new CompressionPlugin({
              algorithm: "gzip",
              test: /\.js(\?.*)?$/i,
              exclude: '/node_modules/',
          }),
          new PreloadWebpackPlugin({
              rel: 'preload',
              as: 'script'
          })
          // new HtmlWebpackPlugin({
          //     template: 'public/index.html'
          // })
      ],
    module: {
        rules: [

        ],
    },
    optimization: {
        minimizer: [new UglifyJsPlugin()],
    },
  },

}
