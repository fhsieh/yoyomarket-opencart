var gulp = require('gulp'),
    less = require('gulp-less');

var css = {
  'admin': {
    src: './admin/view/javascript/bootstrap/opencart/opencart.less',
    dest: './admin/view/javascript/bootstrap/opencart'
  }
};

gulp.task('less', function() {
  return gulp.src(css['admin'].src)
    .pipe(less())
    .pipe(gulp.dest(css['admin'].dest));
});
