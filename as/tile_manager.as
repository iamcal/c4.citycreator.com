
_global.TileManager = function() {
}

TileManager.prototype.initialize = function(node) {

	this.tile_sets = new Array();
	this.current_set = null;
	this.logo = node.attributes.logo;
	this.truck = node.attributes.truck;
	this.sets_loaded = 0;

	gBgColor = node.attributes.bgcolor;
	gCityId = node.attributes.city;

	for (var i=0; i<node.childNodes.length; i++) {
		var child = node.childNodes[i];
		if (child.nodeName == 'pieceset'){
			this.tile_sets[this.tile_sets.length] = new TileSet().initialize(child, this);
		}
	}

	gLoadingManager.loadMovie(this.logo);
	gLoadingManager.loadMovie(this.truck);

	return this;
}

TileManager.prototype.initTiles = function(){

	this.logo_id = getNewDepth();
	this._logo_mc = gMainFrame.getMc().createEmptyMovieClip('logo_mc_'+this.logo_id, this.logo_id);
	this._logo_mc.loadMovie(this.logo);

	this.truck_id = getNewDepth();
	this._truck_mc = gMainFrame.getMc().createEmptyMovieClip('truck_mc_'+this.truck_id, this.truck_id);
	this._truck_mc.loadMovie(this.truck);

	gLoadingManager2.addListener(this._logo_mc, this, this.onLogoLoad);
	gLoadingManager2.addListener(this._truck_mc, this, this.onTruckLoad);


	for (var i=0; i<this.tile_sets.length; i++) {
		this.tile_sets[i].initTiles(i, i==0);
	}
	this.showSet(0);
}

TileManager.prototype.showSet = function(id){

	if (this.current_set != null){
		this.tile_sets[this.current_set].hide();
	}

	this.current_set = id;

	this.tile_sets[this.current_set].show();
}

TileManager.prototype.deleteAll = function(){
	var tiles = gAllTiles;
	for(id in tiles){
		tiles[id].deleteTile();
	}
}

TileManager.prototype.onLogoLoad = function(){
	this._logo_mc._x = 14;
	this._logo_mc._y = 12;
}

TileManager.prototype.onTruckLoad = function(){
	this._truck_mc._x = 656;
	this._truck_mc._y = 400;
}

TileManager.prototype.setLoaded = function(){
	this.sets_loaded++;
	if (this.sets_loaded == this.tile_sets.length){
		this.onTilesLoaded();
	}
}
