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

	if (color){
		color_in(this._mc, color, w, h);
	}else{
		this._mc.attachMovie('spacer', 'spacer', 1);
	}

	this._mc.spacer._width = w;
	this._mc.spacer._height = h;

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

Canvas.prototype.show = function(){
	this._mc._visible = true;
}

Canvas.prototype.hide = function(){
	this._mc._visible = false;
}
