_global.gUidTileSources = new Array();

_global.TileSource = function() {
}

TileSource.prototype.initialize = function(node, parent) {

	this.parent = parent;
	this.node = node;
	this.seq_num = node.attributes.num;
	this.offsetx = node.attributes.offsetx;
	this.offsety = node.attributes.offsety;
	this.src = node.attributes.src; // + '?' + new Date().getTime();
	this.uid = node.attributes.uid;

	gUidTileSources[this.uid] = this;

	//trace("loading "+this.src);

	this.preload_id = gLoadingManager.loadMovie(this.src);

	return this;	
}

TileSource.prototype.initTile = function(canvas){

	this.canvas = canvas;
	this.parent_mc = canvas.getMc();
	this.id = getNewDepth();
	this.preload_mc = gLoadingManager.getMovie(this.preload_id);

	this._mc = this.parent_mc.createEmptyMovieClip('tile_source_mc_'+this.id, this.id);
	this._mc.loadMovie(this.src);

	gLoadingManager2.addListener(this._mc, this, this.onTileSourceLoad);
}


TileSource.prototype.onTileSourceLoad = function(){
	//trace('loaded: '+this._mc._name+' : '+this.src);

	this._mc.source = this;

	this._mc.onPress = function() {
		var temp = new Tile().initialize(this.source, 1);
	}

	var col = this.seq_num % 4;
	var row = (this.seq_num - col) / 4;

	var x = (58 * col);
	var y = (67 * row);

	x += Math.round((57 / 2) - (this.preload_mc._width / 2));
	y += Math.round((66 / 2) - (this.preload_mc._height / 2));

	this._mc._x = x;
	this._mc._y = y;

	this.parent.tileLoaded();
}
