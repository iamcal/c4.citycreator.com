_global.gAllTileSources = new Array();

_global.TileSource = function() {
}

TileSource.prototype.initialize = function(node, parent) {

	this.parent = parent;
	this.node = node;
	//this.piece_id = node.attributes.sysid;
	this.src = node.attributes.src;

	this.preload_id = gLoadingManager.loadMovie(this.src);

	return this;	
}

TileSource.prototype.initTile = function(seq_num){

	this.parent_mc = gMainFrame.getMc();
	this.id = getNewDepth();
	this.seq_num = seq_num;
	this.preload_mc = gLoadingManager.getMovie(this.preload_id);

	var name = 'tile_source_mc_'+this.id;
	gAllTileSources[name] = this;

	this._mc = this.parent_mc.createEmptyMovieClip(name, this.id);
	this._mc.onLoad = function(){
		tile_source = gAllTileSources[this._name];
		tile_source.onTileSourceLoad();
	}
	this._mc.loadMovie(this.src);
}


TileSource.prototype.onTileSourceLoad = function(){

	this._mc.source = this;

	this._mc.onPress = function() {
		trace('this._mc.onPress');
		trace(this.source);
		var x_tile = new Tile().initialize(this.source, 1);
		trace('done');
	}

	var col = this.seq_num % 4;
	var row = (this.seq_num - col) / 4;

	var x = 14 + (58 * col);
	var y = 61 + (67 * row);

	x += Math.round((57 / 2) - (this.preload_mc._width / 2));
	y += Math.round((66 / 2) - (this.preload_mc._height / 2));

	this._mc._x = x;
	this._mc._y = y;
}
