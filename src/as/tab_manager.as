
_global.TabManager = function() {
}

TabManager.prototype.initialize = function(node) {

	this.tabs = new Array();

	for (var i=0; i<node.childNodes.length; i++) {
		var child = node.childNodes[i];
		if (child.nodeName == 'tab'){
			this.tabs[this.tabs.length] = new TabObject().initialize(child, this);
		}
	}

	return this;
}

TabManager.prototype.initTabs = function(){

	for (var i=0; i<this.tabs.length; i++){
		this.tabs[i].initTab(i);
	}
}


// ====================================================================

_global.TabObject = function() {
}


TabObject.prototype.initialize = function(node, manager) {
	this.manager = manager;
	this.node = node;
	this.x = node.attributes.x;
	this.y = node.attributes.y;
	this.src = node.attributes.src;
	this.ison = node.attributes.ison;
	this.url = node.attributes.url;

	gLoadingManager.loadMovie(this.src);

	return this;
}

TabObject.prototype.initTab = function(id){
	this.id = id;
	this.parent_mc = gMainFrame.getMc();

	this.id = getNewDepth();
	this._mc = this.parent_mc.createEmptyMovieClip('tab_mc_'+this.id, this.id);
	this._mc.loadMovie(this.src);

	gLoadingManager2.addListener(this._mc, this, this.onTabLoad);
}

TabObject.prototype.onTabLoad = function(){
	this._mc._x = this.x;
	this._mc._y = this.y;
	this._mc.tab = this;
	if (this.ison){
		this._mc.gotoAndStop(1);
	}else{
		this._mc.gotoAndStop(2);
		this._mc.onPress = function(){
			trace(this.tab.url);
			getURL(this.tab.url, "_self");
		}
	}
}
