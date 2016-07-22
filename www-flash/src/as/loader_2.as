_global.LoadingManager2 = function() {
}

LoadingManager2.prototype.initialize = function() {
	this.movies = new Array();
	this.check_timer = 0;
	return this;
}

LoadingManager2.prototype.startTimer = function(){
	if (!this.check_timer){
		//trace("starting timer");
		this.check_timer = setInterval(this, 'timerTick', 50);
	}
}

LoadingManager2.prototype.stopTimer = function(){
	if (this.check_timer){
		//trace("stopping timer");
		clearInterval(this.check_timer);
		this.check_timer = null;
	}
}

LoadingManager2.prototype.addListener = function(mc, obj, method){

	var entry = new Object();
	entry.mc = mc;
	entry.obj = obj;
	entry.method = method;

	this.movies.push(entry);

	this.startTimer();
}

LoadingManager2.prototype.timerTick = function(){

	for(var i=this.movies.length-1; i>=0; i--){
		if (this.isLoaded(this.movies[i].mc)){
			this.movies[i].method.call(this.movies[i].obj);
			this.movies.splice(i,1);
		}
	}

	if (this.movies.length == 0){
		this.stopTimer();
	}
}

LoadingManager2.prototype.isLoaded = function(mc){
	if (mc.getBytesTotal()){
		if (mc.getBytesLoaded() == mc.getBytesTotal()){
			if (mc._framesloaded == mc._totalframes){
				return 1;
			}
		}
	}
	return 0;
}

_global.gLoadingManager2 = new LoadingManager2().initialize();
