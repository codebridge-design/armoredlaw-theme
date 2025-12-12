// wp-content/themes/cryptowallet/gulpfile.cjs
const path       = require('path');
const { src, dest, series, watch } = require('gulp');
const less       = require('gulp-less');
const postcss    = require('gulp-postcss');
const autoprefix = require('autoprefixer');
const cleanCSS   = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const rename     = require('gulp-rename');
const plumber    = require('gulp-plumber');

const ROOT       = __dirname;
const ENTRY_LESS = path.join(ROOT, 'assets/css/less/main.less');
const WATCH_LESS = path.join(ROOT, 'assets/css/less/**/*.less');
const OUT_DIR  = path.join(ROOT, 'assets/dist/css');
const OUT_NAME = 'main.min.css';

async function clean() {
    const { deleteAsync } = await import('del');
    return deleteAsync([path.join(OUT_DIR, '**/*')]);
}

function styles() {
    return src(ENTRY_LESS, { allowEmpty: false })
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(less())
        .pipe(postcss([autoprefix()]))
        .pipe(cleanCSS({ level: 2 }))
        .pipe(rename(OUT_NAME))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(OUT_DIR));
}

function watcher() {
    watch(WATCH_LESS, styles);
}

exports.clean  = clean;
exports.styles = styles;
exports.build  = series(clean, styles);
exports.watch  = watcher;
exports.default = watcher;
