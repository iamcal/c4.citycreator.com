_global.gAllTiles = new Array();

_global.Tile = function() {
	this.id = 0;
}

Tile.prototype.initialize = function(source, start_dragging, x, y) {

	this.source = source;
	this.start_dragging = start_dragging;
	this.start_x = x;
	this.start_y = y;
	this.offsetx = Number(source.offsetx);
	this.offsety = Number(source.offsety);

	this.id = getNewDepth();
	this.canvas_mc = gMainFrame.getCanvasMc();

	this._mc = this.canvas_mc.createEmptyMovieClip('tile_mc_'+this.id, this.id);
	this._mc._y = -1000;
	this._mc._visible = false;
	this._mc.loadMovie(this.source.src);

	gLoadingManager2.addListener(this._mc, this, this.onTileLoad);

	gAllTiles[this._mc._name] = this;

	return this;
}

Tile.prototype.onTileLoad = function(){

	//trace("tile load");

	this._mc.tile = this;
	this._mc._visible = true;
	this._mc._x = this.source._mc._x - this.canvas_mc._x + this.source.parent.canvas._mc._x;
	this._mc._y = this.source._mc._y - this.canvas_mc._y + this.source.parent.canvas._mc._y;

	this._mc.onPress = function() {
		if (!this.hitTest(_root._xmouse, _root._ymouse, true)){
			return;
		}else{
			trace("hit test ok");
		}

		_global.gBringToFront(this);
		this.onMouseUp = this.onMouseUpFunc;
		this.startDrag();
	}

	this._mc.onMouseUpFunc = function() {
		this.tile.myStopDrag();
		delete this.onMouseUp;
	}

	if (this.start_dragging){
		this._mc.onMouseUp = this._mc.onMouseUpFunc;
		this._mc.startDrag();
	}else{
		this._mc._x = this.start_x;
		this._mc._y = this.start_y;
	}
}

Tile.prototype.myStopDrag = function() {
	this._mc.stopDrag();

	//
	// were we dropped over the trash?
	//
	var mx = _root._xmouse;
	var my = _root._ymouse;

	if ((mx > 656) && (mx < 722) && (my > 400) && (my < 448)){
		this.deleteTile();
		return;
	}

	//
	// snap to grid
	//

	var px = (Math.round((this._mc._x - this.offsetx) / 24) * 24) + this.offsetx;
	var py = (Math.round((this._mc._y - this.offsety) / 12) * 12) + this.offsety;

	this._mc._x = px;
	this._mc._y = py;
}

Tile.prototype.deleteTile = function() {
	delete gAllTiles[this._mc._name];
	this._mc.unloadMovie();
	delete this;
}
