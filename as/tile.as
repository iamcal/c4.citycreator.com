_global.gAllTiles = new Array();

_global.Tile = function() {
	this.id = 0;
}

Tile.prototype.initialize = function(source, start_dragging) {

	this.source = source;
	this.start_dragging = start_dragging;

	this.id = getNewDepth();
	this.canvas_mc = gMainFrame.getCanvasMc();

	var name = 'tile_mc_'+this.id;
	gAllTiles[name] = this;

	this._mc = this.canvas_mc.createEmptyMovieClip(name, this.id);
	this._mc.onLoad = function(){
		var tile = gAllTiles[this._name];
		tile.onTileLoad();
	}
	this._mc.loadMovie(this.source.src);

	return this;
}

Tile.prototype.onTileLoad = function(){

	this._mc.tile = this;
	this._mc._highquality = 0;
	this._mc._visible = true;
	this._mc._x = this.source._mc._x - this.canvas_mc._x + this.source.parent.canvas._mc._x;
	this._mc._y = this.source._mc._y - this.canvas_mc._y + this.source.parent.canvas._mc._y;

	this._mc.onPress = function() {
		this.swapDepths(getNewDepth());
		this.startDrag();
	}

	this._mc.onMouseUp = function() {
		this.tile.myStopDrag();
	}

	if (this.start_dragging){
		this._mc.startDrag();
	}
}

Tile.prototype.myStopDrag = function() {
	this._mc.stopDrag();

	// snap to grid
	this._mc._x = Math.round(this._mc._x / 24) * 24;
	this._mc._y = Math.round(this._mc._y / 12) * 12;
}
