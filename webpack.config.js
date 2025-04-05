const Encore = require('@symfony/webpack-encore');

// Basic configuration
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // .disableVersioning()

    // Dummy entry point (required)
    .addEntry('app', './assets/app.js')

    // Copy all assets exactly as they are
    .copyFiles([
        {
            from: './assets/theme/css',
            to: 'css/[path][name].[ext]'
        },
        {
            from: './assets/theme/js',
            to: 'js/[path][name].[ext]'
        },
        {
            from: './assets/theme/js/apexcharts',
            to: 'js/apexcharts/[name].[ext]',
            pattern: /\.js$/
        },
        {
            from: './assets/theme/images',
            to: 'images/[path][name].[ext]',
            pattern: /\.(png|jpg|jpeg|gif|svg|webp|ico)$/
        },
        {
            from: './assets/theme/font',
            to: 'fonts/[path][name].[ext]'
        },
        {
            from: './assets/theme/icon',
            to: 'icons/[path][name].[ext]'
        }
    ]);

// Export the final configuration
module.exports = Encore.getWebpackConfig();