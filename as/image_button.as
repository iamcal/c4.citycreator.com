_global.ImageButton = function() {
	this.id = 0;
}

ImageButton.prototype.initialize = function(x, y, src, parent) {
	if (!parent) parent = _root;

	this.id = getNewDepth();
	this._mc = parent.createEmptyMovieClip('image_button_mc_' + this.id, this.id);
	this._mc._x = x;
	this._mc._y = y;
	this._mc.button = this;

	this._mc.attachMovie(src, 's', 1);

	this.onClick = function() {}

	this._mc.onPress = function (){
		this.s.gotoAndStop(2);
	}

	this._mc.onReleaseOutside = function (){
		this.s.gotoAndStop(1);
	}

	this._mc.onRelease = function (){
		this.s.gotoAndStop(1);
		this.button.onClick();
	}

	return this;
}
