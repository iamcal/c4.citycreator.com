
_global.BgManager = function() {
}

BgManager.prototype.initialize = function(node) {

	this.bgs = new Array();
	this.current_bg = null;

	for (var i=0; i<node.childNodes.length; i++) {
		var child = node.childNodes[i];
		if (child.nodeName == 'background'){
			this.bgs[this.bgs.length] = new BgObject().initialize(child, this);
		}
	}

	return this;
}

BgManager.prototype.initBgs = function(){

	this.fulls_loaded = 0;

	for (var i=0; i<this.bgs.length; i++){
		this.bgs[i].initBg(i);
	}
}

BgManager.prototype.fullLoaded = function(){
	this.fulls_loaded++;
	if (this.fulls_loaded == this.bgs.length){
		this.onBgsLoaded();
	}
}

BgManager.prototype.setBg = function(id){

	if (this.current_bg){
		this.bgs[this.current_bg].hide();
	}

	this.current_bg = id;

	this.bgs[this.current_bg].show();
}

// ====================================================================

_global.BgObject = function() {
}


BgObject.prototype.initialize = function(node, manager) {
	this.manager = manager;
	this.node = node;
	this.x = node.attributes.x;
	this.y = node.attributes.y;
	this.w = node.attributes.w;
	this.h = node.attributes.h;
	this.src = node.attributes.src;
	this.thumb = node.attributes.thumb;

	gLoadingManager.loadMovie(this.src);
	gLoadingManager.loadMovie(this.thumb);

	return this;
}

BgObject.prototype.initBg = function(id){
	this.id = id;
	this.parent_mc = gMainFrame.getMc();

	this.thumb_id = getNewDepth();
	this._thumb_mc = this.parent_mc.createEmptyMovieClip('bg_thumb_mc_'+this.thumb_id, this.thumb_id);
	this._thumb_mc.loadMovie(this.thumb);

	this.full_id = getNewDepth();
	this._full_mc = this.parent_mc.createEmptyMovieClip('bg_full_mc_'+this.full_id, this.full_id);
	this._full_mc._x = -1000;
	this._full_mc.loadMovie(this.src);

	gLoadingManager2.addListener(this._thumb_mc, this, this.onBgThumbLoad);
	gLoadingManager2.addListener(this._full_mc, this, this.onBgFullLoad);
}

BgObject.prototype.onBgThumbLoad = function(){
	this._thumb_mc._x = this.x;
	this._thumb_mc._y = this.y;
	this._thumb_mc.bg = this;
	this._thumb_mc.onPress = function(){
		this.bg.manager.setBg(this.bg.id);
	}
}

BgObject.prototype.onBgFullLoad = function(){
	this._full_mc._visible = false;
	this._full_mc._x = 257;
	this._full_mc._y = 28;
	this.manager.fullLoaded();
}

BgObject.prototype.show = function(){
	this._full_mc._visible = true;
}

BgObject.prototype.hide = function(){
	this._full_mc._visible = false;
}
