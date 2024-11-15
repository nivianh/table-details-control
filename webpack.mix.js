const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/plugins/${directory}`
const dist = `public/vendor/core/plugins/${directory}`

mix
    .js(`${source}/resources/js/table.js`, `${dist}/js`)
    .sass(`${source}/resources/sass/table.scss`, `${dist}/css`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/js/table.js`, `${source}/public/js`)
        .copy(`${dist}/css/table.css`, `${source}/public/css`)
}
