const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('./public/')
    .setPublicPath('./')
    .setManifestKeyPrefix('')

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(false)
    .enableVersioning(false)
    .disableSingleRuntimeChunk()

    .addStyleEntry('app', './assets/css/app.scss')
    .addStyleEntry('simple', './assets/css/simple.scss')
    .enableSassLoader()
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();