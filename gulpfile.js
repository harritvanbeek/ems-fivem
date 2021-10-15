//********************************************************//
// you need change the fileName in what effer
// want to have search for fileName, if you have 
// changed this then you need to remeber the name of it.
//
// make new folde in the following order 
// 
// dev
// dev/controller
// dev/js
// dev/scss 
// 
// make new files in the follwing order
// 
// dev/js/fileName.js
// dev/sccs/fileName.sccs
// 
// now you can call you css and js in you html page
// 
// the controller you can use this for javascript 
// libary like as angulerjs, reactjs ect
// 
//********************************************************//

var 	gulp 			= 	require('gulp'),
		rename 			= 	require('gulp-rename'),

		//css related plugins
		sass			=	require('gulp-sass'),
		autoprefixer	=	require('gulp-autoprefixer'),
		sourcemaps		=	require('gulp-sourcemaps'),
		uglify			=	require('gulp-uglify'),
		plumber			=	require('gulp-plumber'),
		
		//JS related plugins
		concat			=	require('gulp-concat'),		
		browserSync		=	require('browser-sync').create(),

		//change your filename !requered
		fileName 		=	"boann",

		//change template foldername or leave empty for the root folder
		template 		=	"",
				
		//change admin foldername or leave empty for the root folder
		adminFolder		=	"",
		devFolder		=	adminFolder+"dev",
		
		//styles
		stylesDEV		=	"./"+devFolder+"/scss/"+fileName+".scss",
		styleWatch		=	"./"+devFolder+"/scss/**/*.scss",
		
		//js routing
		jsDEV			=	"./"+devFolder+"/js/**/*.js",
		jsWatch			=	"./"+devFolder+"/js/**/*.js",

		//controllers
		controlerDEV	=	"./"+devFolder+"/controller/**/*.js",
		controlerWatch	=	"./"+devFolder+"/controller/**/*.js",
		
		//Online folders
		styleLOC		=	"./"+adminFolder+"templates/"+template+"/css/",
		jsLOC			=	"./"+adminFolder+"templates/"+template+"/js/";
		
function browser_sync(){
	browserSync.init();	
}

function reload(done){
	browserSync.reload();
	done();
}

function style(done){
	gulp.src(stylesDEV)
        .pipe(sourcemaps.init())
		.pipe(plumber())
		.pipe( sass({ errorLogToConsole: true, outputStyle: 'compressed'}) )
		.on('error', console.error.bind( console ) )
		.pipe(autoprefixer({ browers:['last 2 versions'], cascade:false }))
		.pipe( rename({suffix:'.min'}) )
		.pipe(sourcemaps.write('./sourcemaps/'))
		.pipe(gulp.dest(styleLOC))
		.pipe( browserSync.stream({injectChanges:true}) );
	done();
}

function scripts(done){
	gulp.src([jsDEV, "!./dev/js/**/*.min.js"])
		.pipe(sourcemaps.init())
		.pipe(concat(fileName+".js"))
		.pipe(plumber())
		.pipe(uglify())
		.pipe(rename({suffix:'.min'}))
		.pipe(sourcemaps.write('./sourcemaps/'))
		.pipe(gulp.dest(jsLOC))
		.pipe( browserSync.stream() );
	done();
}

function scripts01(done){
	gulp.src([jsDEV, "!./dev/js/**/*.min.js"])
		.pipe(sourcemaps.init())
		.pipe(concat(fileName+".js"))
		.pipe(uglify())
		.pipe(rename({suffix:'.min'}))
		.pipe(sourcemaps.write('./sourcemaps/'))
		.pipe(gulp.dest(jsLOC))
		.pipe( browserSync.stream() );
	done();
}

function controllers(done){
	gulp.src([controlerDEV, "!./dev/controller/**/*.min.js"])
		.pipe(sourcemaps.init())
		.pipe(concat(fileName+".controller.js"))
		.pipe(uglify())
		.pipe(rename({suffix:'.min'}))
		.pipe(sourcemaps.write('./sourcemaps/'))
		.pipe(gulp.dest(jsLOC))
		.pipe( browserSync.stream() );
	done();
}

function watch_files(){
 gulp.watch(styleWatch, gulp.series(style));
 gulp.watch(jsWatch, gulp.series(scripts01, reload));
 gulp.watch(controlerWatch, gulp.series(controllers, reload));
 gulp.watch("**/**/*.html", gulp.series(reload));
 gulp.watch("**/**/*.php", gulp.series(reload));
}

gulp.task("style", style);
gulp.task("scripts", scripts01);
gulp.task("controllers", controllers);
gulp.task("browser_sync", browser_sync);
gulp.task("watch", gulp.parallel(browser_sync, watch_files));
gulp.task("default", gulp.parallel(style, scripts, controllers, "watch"));