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

TileSet.prototype.initTiles = function(){
	for (var i=0; i<this.tiles.length; i++) {
		this.tiles[i].initTile(i);
	}
}
