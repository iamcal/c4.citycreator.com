_global.MyLabel = function() {
	this.id = 0;
	this.text_format = new TextFormat('arial', 14, 0x000000);
}

MyLabel.prototype.initialize = function(x, y, caption, parent) {
	if (!parent) parent = _root;

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('button_mc_' + this.id, this.id);
	this._mc._x = x;
	this._mc._y = y;

	this._mc.createTextField("_tf", 0, 0, 0, 10, 10);
	this._mc._tf.setNewTextFormat(this.text_format);
	this.setCaption(caption);

	return this;
}

MyLabel.prototype.setCaption = function(caption) {

	var extent = this.text_format.getTextExtent(caption);

	this._mc._tf._x = 0;
	this._mc._tf._y = 0;
	this._mc._tf._width = extent.width * 2;
	this._mc._tf._height = extent.height * 2;
	this._mc._tf.text = caption;
}
