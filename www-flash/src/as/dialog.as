_global.Dialog = function() {
	this.id = 0;
}

Dialog.prototype.initialize = function(x, y, src, parent) {
	if (!parent) parent = _root;

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('dialog_mc_' + this.id, this.id);
	this._mc._x = x;
	this._mc._y = y;
	this._mc.dialog = this;

	this.hide();

	this._mc.attachMovie(src, 's', 1);

	make_dragable_area(this._mc, 0, 0, this._mc._width, 27);

	//set_hit_area(this._mc, 0, 0, this._mc._width, 27);

	this.close_button = new ImageButton().initialize(x, y, 'btn_close', this._mc);
	this.close_button.dialog = this;
	this.close_button.onClick = function(){ this.dialog.hide(); }

	this._mc.onDragStart = function() {
		this.bringToFront();
	}

	return this;
}

Dialog.prototype.hide = function() {
	this._mc._visible = false;
}

Dialog.prototype.show = function() {
	this._mc.bringToFront();
	this._mc._visible = true;
}

Dialog.prototype.showAt = function(x, y) {
	this._mc._x = x;
	this._mc._y = y;
	this.show();
}
