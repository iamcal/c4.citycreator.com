
// ##############################################################################

_global.PieceManager = function() {
}

PieceManager.prototype.initialize = function(node) {

	this.sets = new Array();

	trace("loading piece manager ");

	for (var i=0; i<node.childNodes.length; i++) {
		var child = node.childNodes[i];
		if (child.nodeName == 'pieceset'){
			this.sets[this.sets.length] = new PieceSet().initialize(child);
		}
	}

	return this;
}

PieceManager.prototype.positionPieces = function(){

	for (var i=0; i<this.sets.length; i++) {

		this.sets[i].positionPieces();
	}
}

// ##############################################################################

_global.PieceSet = function() {
	this._piece_count = 0;
}

PieceSet.prototype.initialize = function(node) {

	this.pieces = new Array();
	this.node = node;
	this.tab_id = node.attributes.id;

	trace("loading piece set "+this.tab_id);

	for (var i=0; i<node.childNodes.length; i++) {
		var child = node.childNodes[i];
		if (child.nodeName == 'piece'){
			this.pieces[this.pieces.length] = new PieceTemplate().initialize(child, this);
		}
	}

	return this;
}

PieceSet.prototype.getSeqNum = function(){
	this._piece_count++;
	return this._piece_count-1;
}

PieceSet.prototype.positionPieces = function(){

	for (var i=0; i<this.pieces.length; i++) {

		this.pieces[i].positionPiece();
	}
}


// ##############################################################################

_global.PieceTemplate = function() {
}

PieceTemplate.prototype.initialize = function(node, parent) {

	this.parent = parent;
	this.node = node;
	this.piece_id = node.attributes.sysid;
	this.src = node.attributes.src;

	trace("loading piece "+this.piece_id);
	trace("src: "+this.src);

	this.loading_id = gLoadingManager.loadMovie(this.src);

	return this;	
}

PieceTemplate.prototype.positionPiece = function(){

	trace("positionPiece");

	var mc_template = gLoadingManager.movies[this.loading_id].mc;

	trace(mc_template);

	this.mc_id = getNewDepth();

	this.mc = mc_template.duplicateMovieClip('piece_template_mc'+this.mc_id, this.mc_id);
	this.mc = eval('piece_template_mc'+this.mc_id);

	trace(this.mc);

	this.mc = mc_template;

	this.seq_num = this.parent.getSeqNum();

	var col = this.seq_num % 4;
	var row = (this.seq_num - col) / 4;

	trace(col+','+row);

	this.mc._parent = _root;
	this.mc._x = 14 + (58 * col);
	this.mc._y = 61 + (67 * row);
	//this.mc._width = 10;
	//this.mc._height = 10;
	this.mc._visible = true;

	this.mc.onPress = function(){

		trace(this);

		var new_id = getNewDepth();
		var new_mc = this.duplicateMovieClip('piece_mc'+new_id, new_id);

		trace(new_mc);

		new_mc._visible = true;
		new_mc._x = this._x + 20;
		new_mc._y = this._y;
		new_mc._parent = this._parent;

		new_mc.onRelease = function(){ this.stopDrag(); }
		new_mc.startDrag();
	}
}

// ##############################################################################
