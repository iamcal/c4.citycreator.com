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

	return this;
}

TileSet.prototype.initTiles = function(id){

	//
	// first, create a canvas for this set
	//

	var color = (id==0)?0x0000ff:0x00ff00;

	this.canvas = new Canvas().initialize(13, 60, 233, 336, gMainFrame.getMc(), color);
	this.hide();

	for (var i=0; i<this.tiles.length; i++) {
		this.tiles[i].initTile(this.canvas, i);
	}
}

Tileset.prototype.show = function(){
	this.canvas.show();
}

Tileset.prototype.hide = function(){
	this.canvas.hide();
}
