_global.ProgressBar = function() {
	this.id = 0;
	this.border_col = 0x000000;
	this.progress_col = 0xCCCCFF;
}

ProgressBar.prototype.initialize = function(x, y, w, h, parent) {
	if (!parent) parent = _root;

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('button_mc_' + this.id, this.id);
	this._mc._x = x;
	this._mc._y = y;
	this._mc.bar = this;

	this.w = w;
	this.h = h;

	this.progress = 0;
	this._draw();

	return this;
}

ProgressBar.prototype.setProgress = function(value) {
	this.progress = value;
	this._draw();
}

ProgressBar.prototype._draw = function() {

	this._mc.clear();

	var p_width = Math.round((this.w-1) * (this.progress / 100));

	this._mc.lineStyle();
	this._mc.beginFill(this.progress_col);
	this._mc.moveTo(2,2);
	this._mc.lineTo(p_width, 2);
	this._mc.lineTo(p_width, this.h-1);
	this._mc.lineTo(2, this.h-1);
	this._mc.lineTo(2, 2);
	this._mc.endFill();
	
	this._mc.lineStyle(1, this.border_col);
	this._mc.moveTo(0,0);
	this._mc.lineTo(this.w, 0);
	this._mc.lineTo(this.w, this.h);
	this._mc.lineTo(0, this.h);
	this._mc.lineTo(0, 0);
}
