//setter for onLoad
sol = function (f){
	if (__onLoadHandler__ == undefined) _global.__onLoadHandler__ = {};
	__onLoadHandler__[this] = f;
};

//getter for onLoad
gol = function(){
	return __onLoadHandler__[this];
}

//assign property-handlers for onLoad (courtesy of Gnut)
MovieClip.prototype.addProperty("onLoad", gol, sol);
