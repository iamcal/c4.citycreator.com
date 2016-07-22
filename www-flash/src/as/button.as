_global.MyButton = function() {
	this.id = 0;
	this.hi_col = 0xFFFFFF;
	this.sh_col = 0x9999CC;
	this.bg_col = 0xCCCCFF;
	this.text_format = new TextFormat('arial', 12, 0x000000);
	this.text_format.align = 'center';
	this.text_format.bold = true;
}

MyButton.prototype.initialize = function(x, y, w, h, caption, parent) {
	if (!parent) parent = _root;

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('button_mc_' + this.id, this.id);
	this._mc._x = x;
	this._mc._y = y;
	this._mc.button = this;

	draw_shadow_box(this._mc, w, h, this.bg_col, this.hi_col, this.sh_col);

	this._mc._width = w;
	this._mc._height = h;

	this.w = w;
	this.h = h;
	this.onClick = function() {}

	this._mc.onPress   = function (){
		draw_shadow_box(this, this.button.w, this.button.h, this.button.bg_col, this.button.sh_col, this.button.hi_col);
		this._tf._x++;
		this._tf._y++;
		this.onMouseUp = this.onUnpress;
		this.button.onClick();
	}

	this._mc.onUnpress = function (){		
		draw_shadow_box(this, this.button.w, this.button.h, this.button.bg_col, this.button.hi_col, this.button.sh_col);
		this._tf._x--;
		this._tf._y--;
		delete this.onMouseUp;
	}

	this._mc.createTextField("_tf", 0, 0, t_y, w, h);
	this._mc._tf.setNewTextFormat(this.text_format);
	this.setCaption(caption);

	return this;
}

MyButton.prototype.setCaption = function(caption) {

	var extent = this.text_format.getTextExtent(caption);

	var t_h = extent.height;
	var t_y = Math.floor((this.h/2) - (t_h/2)) - 2;

	this._mc._tf._x = 0;
	this._mc._tf._y = t_y;
	this._mc._tf._width = this.w;
	this._mc._tf._height = this.h;
	this._mc._tf.text = caption;
}
