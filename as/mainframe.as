_global.MainFrame = function() {
	this.id = 0;
}

MainFrame.prototype.initialize = function(x, y, w, h, parent) {
	if (!parent) parent = _root;

	this.bgid = getNewDepth();
	this._bg = parent.createEmptyMovieClip('frame_bg_mc_' + this.bgid, this.bgid);
	this._bg._x = 0;
	this._bg._y = 0;
	this._bg._highquality = 0;
	color_in(this._bg, 0xffcccc, w, h-10);

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('frame_mc_' + this.id, this.id);
	this._mc.attachMovie('frame', 's', 1);
	this._mc._x = x;
	this._mc._y = y;
	this._mc._width = w;
	this._mc._height = h;
	this._mc._highquality = 0;

	this.canvas = new Canvas().initialize(257, 28, 469, 364, this._mc, 0x00ff00);

	return this;
}

MainFrame.prototype.getMc = function(){
	return this._mc;
}

MainFrame.prototype.getCanvasMc = function(){
	return this.canvas.getMc();
}

MainFrame.prototype.bringCanvasForward = function(){
	return this.canvas.bringForward();
}
