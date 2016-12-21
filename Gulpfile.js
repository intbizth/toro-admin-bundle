var gulp = require('gulp');
var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var debug = require('gulp-debug');
var order = require('gulp-order');
var merge = require('merge-stream');
var gutil = require('gulp-util');

var env = gutil.env.env;
var stage = gutil.env.stage;

var rootPath = '../../../web/assets/admin/';
var nodePath = '../../../node_modules/';

if ('dev' === stage) {
    rootPath = "../" + rootPath;
    nodePath = "../" + nodePath;
}

var paths = {
    js: [
        'Resources/private/boot.js',
        nodePath + 'jquery/dist/jquery.min.js',
        nodePath + 'jquery-serializejson/jquery.serializejson.js',
        nodePath + 'jquery.maskedinput/src/jquery.maskedinput.js',
        nodePath + 'selectize/dist/js/standalone/selectize.js',
        nodePath + 'sortablejs/Sortable.js',
        nodePath + 'sortablejs/jquery.binding.js',
        'Resources/private/bootstrap/dist/js/bootstrap.min.js',
        nodePath + 'bootstrap-validator/dist/validator.js',
        nodePath + 'moment/min/moment-with-locales.min.js',
        nodePath + 'dropzone/dist/min/dropzone.min.js',
        nodePath + 'eonasdan-bootstrap-datetimepicker/src/js/bootstrap-datetimepicker.js',
        nodePath + 'bootbox/bootbox.min.js',
        nodePath + 'select2/dist/js/select2.full.min.js',
        nodePath + 'switchery/standalone/switchery.js',
        nodePath + 'noty/js/noty/packaged/jquery.noty.packaged.min.js',
        nodePath + 'dragula/dist/dragula.min.js',
        'Resources/private/nifty.js',
        'Resources/private/js/**',
        'Resources/private/start.js'
    ],
    sass: [
        'Resources/private/sass/**'
    ],
    css: [
        nodePath + 'selectize/dist/css/selectize.css',
        nodePath + 'selectize/dist/css/selectize.bootstrap3.css',
        nodePath + 'font-awesome/css/font-awesome.min.css',
        'Resources/private/bootstrap/dist/css/bootstrap.min.css',
        nodePath + 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
        nodePath + 'dropzone/dist/min/basic.min.css',
        nodePath + 'dropzone/dist/min/dropzone.min.css',
        nodePath + 'magic-check/css/magic-check.css',
        nodePath + 'animate.css/animate.min.css',
        nodePath + 'flag-icon-css/css/flag-icon.min.css',
        nodePath + 'dragula/dist/dragula.min.css',
        'Resources/private/icons/themify-icons/themify-icons.css',
        'Resources/private/icons/solid-icons/premium-solid-icons.css',
        'Resources/private/icons/line-icons/premium-line-icons.css',
        'Resources/private/css/**'
    ],
    copy: [
        ['images', 'Resources/private/images/**'],
        ['fonts', nodePath + 'font-awesome/fonts/**'],
        ['fonts', 'Resources/private/bootstrap/dist/fonts/**'],
        ['flags', nodePath + 'flag-icon-css/flags/**'],
        ['css/fonts', 'Resources/private/icons/themify-icons/fonts/**'],
        ['css/fonts', 'Resources/private/icons/solid-icons/fonts/**'],
        ['css/fonts', 'Resources/private/icons/line-icons/fonts/**'],
        ['handlebars', nodePath + 'handlebars/dist/**'],
        ['handlebars-intl', nodePath + 'handlebars-intl/dist/**']
    ]
};

gulp.task('script', function () {
    return gulp.src(paths.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(rootPath + 'js/'));
});

gulp.task('style', function () {
    var cssStream = gulp.src(paths.css)
            .pipe(concat('css-files.css'))
        ;

    var sassStream = gulp.src(paths.sass)
            .pipe(sass())
            .pipe(concat('sass-files.scss'))
        ;

    return merge(cssStream, sassStream)
        .pipe(order(['css-files.css', 'sass-files.scss']))
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(rootPath + 'css/'))
        ;
});

gulp.task('copy', function () {
    for (var i = 0; i < paths.copy.length; i++) {
        var copy = paths.copy[i];
        gulp.src(copy[1]).pipe(gulp.dest(rootPath + copy[0]));
    }
});

gulp.task('watch', function () {
    gulp.watch(paths.js, ['script']);
    gulp.watch(paths.sass, ['style']);
    gulp.watch(paths.css, ['style']);
});

gulp.task('default', ['script', 'style', 'copy']);
