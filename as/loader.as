
// ====================================================================== //

_global.LoadingManager = function() {
}

LoadingManager.prototype.initialize = function() {
	this.num = 0;
	this.movies = new Array();
	this.check_timer = null;
	return this;
}

LoadingManager.prototype.getNum = function(){
	this.num++;
	return this.num;
}

LoadingManager.prototype.loadMovie = function(src) {
	this.movies[this.movies.length] = new LoadingMovie().initialize(this, src);
	return this.movies.length-1;
}

LoadingManager.prototype.getMovie = function(id){
	return this.movies[id].mc;
}

LoadingManager.prototype.start_checking = function(){
	//return;

	if (!this.check_timer){
		trace('starting timer');
		this.check_timer = setInterval(this, 'check_loaded', 1000);
	}else{
		trace('wont start timer');
	}
}

LoadingManager.prototype.stop_checking = function(){
	if (this.check_timer){
		clearInterval(this.check_timer);
		this.check_timer = null;
	}
}

LoadingManager.prototype.check_loaded = function(){

	var l_loaded = 1;

	for(var i=0; i<this.movies.length; i++){
		var mov = this.movies[i];

		if (!mov.loaded){
			trace("clip "+i+" has NOT loaded");
			l_loaded = 0;
		}
	}

	if (l_loaded){
		this.stop_checking();
		this.onLoaded();
	}
}

LoadingManager.prototype.onLoaded = function(){
	trace("loadingManager.onLoaded()");
}

LoadingManager.prototype.clipLoaded = function(clip){
	trace('clip loaded');
	for(var i=0; i<this.movies.length; i++){
		var mov = this.movies[i];
		if (mov.mc == clip){
			mov.loaded = 1;
		}
	}
}

_global.gLoadingManager = new LoadingManager().initialize();


// ====================================================================== //

_global.LoadingMovie = function() {
	this.num = 0;
	this.temp = 0;
}

LoadingMovie.prototype.initialize = function(parent, src) {

	this.num = parent.getNum();
	this.parent = parent;
	this.src = src;
	this.loaded = 0;

	this.id = getNewDepth();
	this.mc = _root.createEmptyMovieClip('loading_movie_mc' + this.id, this.id);
	this.mc._x = -100;
	this.mc._y = -100;

	this.mc.onLoad = function() {
		gLoadingManager.clipLoaded(this);
	}
	this.mc.loadMovie(this.src);

	return this;
}

// ====================================================================== //
