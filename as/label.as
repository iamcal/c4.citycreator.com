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
	this.setStyle();
	this.setCaption(caption);

	return this;
}


MyLabel.prototype.setStyle = function() {
	this._mc._tf.setNewTextFormat(this.text_format);
}

MyLabel.prototype.setCaption = function(caption) {

	var extent = this.text_format.getTextExtent(caption);

	this._mc._tf._x = 0;
	this._mc._tf._y = 0;
	this._mc._tf._width = extent.width * 2;
	this._mc._tf._height = extent.height * 2;
	this._mc._tf.text = caption;
}

// ===================================================================

_global.CenteredLabel = function() {
	this.id = 0;
	this.text_format = new TextFormat('arial', 12, 0x000000);
	this.text_format.align = 'center';
}

CenteredLabel.prototype = new MyLabel();

CenteredLabel.prototype.initialize = function(x, y, w, caption, parent) {
	this.w = w;

	super.initialize(x, y, caption, parent);

	return this;
}


CenteredLabel.prototype.setCaption = function(caption) {

	var extent = this.text_format.getTextExtent(caption);

	this._mc._tf._x = 0;
	this._mc._tf._y = 0;
	this._mc._tf._width = this.w;
	this._mc._tf._height = extent.height * 2;
	this._mc._tf.text = caption;

}
