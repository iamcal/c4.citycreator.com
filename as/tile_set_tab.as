_global.gAllTileSetTabs = new Array();

_global.TileSetTab = function() {
}

TileSetTab.prototype.initialize = function(node, parent_set) {

	this.parent_set = parent_set;
	this.node = node;
	this.src = node.attributes.tabsrc;

	this.preload_id = gLoadingManager.loadMovie(this.src);

	return this;
}

TileSetTab.prototype.initTab = function(active){

	this.id = getNewDepth();
	this.start_active = active;
	this.parent_mc = gMainFrame.getMc();
	this.preload_mc = gLoadingManager.getMovie(this.preload_id);

	var name = 'tile_set__tab_mc_'+this.id;
	gAllTileSetTabs[name] = this;

	this._mc = this.parent_mc.createEmptyMovieClip(name, this.id);
	this._mc.onLoad = function(){
		var tilesettab = gAllTileSetTabs[this._name];
		tilesettab.onTileSetTabLoad();
	}
	this._mc.loadMovie(this.src);
}


TileSetTab.prototype.onTileSetTabLoad = function(){

	if (this.start_active){
		this.down();
	}else{
		this.up();
	}

	this._mc._x = this.node.attributes.tabx;
	this._mc._y = this.node.attributes.taby;
	this._mc.tab = this;
	this._mc.onPress = function() {
		this.tab.onPress();
	}
}

TileSetTab.prototype.onPress = function(){
	this.parent_set.switchTo();
}

TileSetTab.prototype.down = function(){
	this._mc.gotoAndStop(1);
}

TileSetTab.prototype.up = function(){
	this._mc.gotoAndStop(2);
}
