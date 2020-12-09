const gulp = require('gulp');
const livereload = require('gulp-livereload')
const uglify = require('gulp-uglifyjs');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const imagemin = require('gulp-imagemin');
const pngquant = require('imagemin-pngquant');

gulp.task('imagemin', function () {
    return gulp.src('./themes/custom/omedia/images/*')
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest('./themes/custom/omedia/images'));
});

gulp.task('sass', function () {
  gulp.src('./themes/custom/omedia/sass/**/*.scss')
    .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 7', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./themes/custom/omedia/css'));
});


gulp.task('uglify', function() {
  gulp.src('./themes/custom/omedia/lib/*.js')
    .pipe(uglify('main.js'))
    .pipe(gulp.dest('./themes/custom/omedia/js'))
});

gulp.task('watch', function(){
    livereload.listen();

    gulp.watch('./themes/custom/omedia/sass/**/*.scss', ['sass']);
    gulp.watch('./themes/custom/omedia/lib/*.js', ['uglify']);
    gulp.watch(['./themes/custom/omedia/css/style.css', './themes/custom/omedia/themes/**/*.twig', './themes/custom/omedia/js/*.js'], function (files){
        livereload.changed(files)
    });
});
