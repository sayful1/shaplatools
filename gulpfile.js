const gulp = require('gulp');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const sassOptions = {
    errLogToConsole: true,
    outputStyle: 'compressed'
};
const autoprefixerOptions = {
    browsers: ['last 5 versions', '> 5%', 'Firefox ESR']
};

gulp.task('scss', function () {
    gulp.src('./assets/scss/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass(sassOptions).on('error', sass.logError))
        .pipe(autoprefixer(autoprefixerOptions))
        .pipe(gulp.dest('./assets/css'));
});

gulp.task('watch', function () {
    gulp.watch('./assets/scss/*.scss', ['scss']);
});

gulp.task('default', ['scss', 'watch']);
