
_global.TileManager = function() {
}

TileManager.prototype.initialize = function(node) {

	this.tile_sets = new Array();

	trace("loading tile manager...");

	for (var i=0; i<node.childNodes.length; i++) {
		var child = node.childNodes[i];
		if (child.nodeName == 'pieceset'){
			this.tile_sets[this.tile_sets.length] = new TileSet().initialize(child, this);
		}
	}

	return this;
}

TileManager.prototype.initTiles = function(){

	for (var i=0; i<this.tile_sets.length; i++) {

		this.tile_sets[i].initTiles();
	}
}
