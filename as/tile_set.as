_global.TileSet = function() {
}

TileSet.prototype.initialize = function(node, manager) {

	this.manager = manager;
	this.tiles = new Array();
	this.node = node;
	//this.tab_id = node.attributes.id;

	for (var i=0; i<node.childNodes.length; i++) {
		var child = node.childNodes[i];
		if (child.nodeName == 'piece'){
			this.tiles[this.tiles.length] = new TileSource().initialize(child, this);
		}
	}

	this.tab = new TileSetTab().initialize(node, this);

	return this;
}

TileSet.prototype.initTiles = function(id, active){

	this.tileset_id = id;

	trace("TileSet.prototype.initTiles");

	this.tab.initTab(active);

	//
	// first, create a canvas for this set
	//

	var color = (id==0)?0x0000ff:0x00ff00;

	this.canvas = new Canvas().initialize(13, 60, 233, 336, gMainFrame.getMc(), color);

	if (active){
		this.show();
	}else{
		this.hide();
	}

	for (var i=0; i<this.tiles.length; i++) {
		this.tiles[i].initTile(this.canvas, i);
	}
}

Tileset.prototype.show = function(){
	this.canvas.show();
	this.tab.down();
}

Tileset.prototype.hide = function(){
	this.canvas.hide();
	this.tab.up();
}

Tileset.prototype.switchTo = function(){
	this.manager.showSet(this.tileset_id);
}
