_global.CCDialog = function() {
	this.id = 0;
}

CCDialog.prototype.initialize = function(w, h, caption, parent) {
	if (!parent) parent = _root;

	this.w = w;
	this.h = h;

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('ccdialog_mc_' + this.id, this.id);
	this._mc._x = 0;
	this._mc._y = 0;
	this._mc.dialog = this;

	color_in(this._mc, 0x999900, this.w, this.h);

	this.hide();

	this._mc_top_left  = create_simple_mc('dialog_top_left',  0, 0, 6, 27, this._mc);
	this._mc_top_mid   = create_simple_mc('dialog_top_mid',   6, 0, this.w-12, 27, this._mc);
	this._mc_top_right = create_simple_mc('dialog_top_right', w-6, 0, 6, 27, this._mc);

	this._mc_bottom_left  = create_simple_mc('dialog_bottom_left',  0, h-12, 5, 12, this._mc);
	this._mc_bottom_mid   = create_simple_mc('dialog_bottom_mid',   5, h-12, this.w-10, 12, this._mc);
	this._mc_bottom_right = create_simple_mc('dialog_bottom_right', w-5, h-12, 5, 12, this._mc);

	this._mc_left  = create_simple_mc('dialog_left',  0, 27, 5, h-39, this._mc);
	this._mc_right = create_simple_mc('dialog_right', w-5, 27, 5, h-39, this._mc);

	this.caption = new CenteredLabel().initialize(0, 10, 200, caption, this._mc);
	this.caption.text_format.bold = true;
	this.caption.setStyle();
	this.caption.setCaption(caption);

	make_dragable_area(this._mc, 0, 0, this.w, 27);

	return this;
}

CCDialog.prototype.hide = function() {
	this._mc._visible = false;
}

CCDialog.prototype.show = function() {
	this._mc.bringToFront();
	this._mc._visible = true;
}

CCDialog.prototype.showAt = function(x, y) {
	this._mc._x = x;
	this._mc._y = y;
	this.show();
}

function create_simple_mc(src, x, y, w, h, parent){

	var id = getNewDepth();

	mc = parent.attachMovie(src, 'simple_mc_' + id, id);

	mc._x = x;
	mc._y = y;

	if (mc._width < w){
		create_simple_mc(src, x+mc._width, y, w-mc._width, h, parent);
	}

	if (mc._height < h){
		create_simple_mc(src, x, y+mc._height, w, h-mc._height, parent);
	}

	if (mc._height > h){
		mc._height = h;
	}

	if (mc._width > w){
		mc._width = w;
	}
}
