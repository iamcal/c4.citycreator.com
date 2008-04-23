// ================================================================== //

sol = function (f){
	if (__onLoadHandler__ == undefined) _global.__onLoadHandler__ = {};
	__onLoadHandler__[this] = f;
};

gol = function(){
	return __onLoadHandler__[this];
}

MovieClip.prototype.addProperty("onLoad", gol, sol);

// ================================================================== //

g_set_parent = function (f){
	if (__parentHandler__ == undefined) _global.__parentHandler__ = {};
	__parentHandler__[this] = f;
};

g_get_parent = function(){
	return __parentHandler__[this];
}

MovieClip.prototype.addProperty("parent", g_get_parent, g_set_parent);

// ================================================================== //
