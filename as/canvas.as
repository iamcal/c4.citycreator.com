_global.Canvas = function() {
	this.id = 0;
}

Canvas.prototype.initialize = function(x, y, w, h, parent, color) {
	if (!parent) parent = _root;

	this.offsetX = x;
	this.offsetY = y;

	this.bgid = getNewDepth();
	this._mc = parent.createEmptyMovieClip('canvas_mc_' + this.bgid, this.bgid);
	this._mc._x = x;
	this._mc._y = y;
	this._mc._highquality = 0;

	if (color){
		color_in(this._mc, color, w, h);
	}

	this._mc._width = w;
	this._mc._height = h;

	return this;
}

Canvas.prototype.getMc = function(){
	return this._mc;
}

Canvas.prototype.spawnPiece = function(template){
	//
}

Canvas.prototype.bringForward = function(){
	this._mc.swapDepths(getNewDepth());
}