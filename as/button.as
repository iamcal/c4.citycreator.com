_global.MyButton = function() {
	this.id = 0;
	this.hi_col = 0xFFFFFF;
	this.sh_col = 0x9999CC;
	this.bg_col = 0xCCCCFF;
}

MyButton.prototype.initialize = function(x, y, w, h, parent) {
	if (!parent) parent = _root;

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('button_mc_' + this.id, this.id);
	this._mc._x = x;
	this._mc._y = y;
	this._mc._highquality = 0;
	this._mc.button = this;

	draw_shadow_box(this._mc, w, h, this.bg_col, this.hi_col, this.sh_col);

	this._mc._width = w;
	this._mc._height = h;

	this.w = w;
	this.h = h;
	this.onClick = function() {}

	this._mc.onPress   = function (){
		draw_shadow_box(this, this.button.w, this.button.h, this.button.bg_col, this.button.sh_col, this.button.hi_col);
		this.onMouseUp = this.onUnpress;
		this.button.onClick();
	}
	this._mc.onUnpress = function (){		
		draw_shadow_box(this, this.button.w, this.button.h, this.button.bg_col, this.button.hi_col, this.button.sh_col);
		delete this.onMouseUp;
	}

	return this;
}
