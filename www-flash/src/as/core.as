_global.newDepth = 100;
_global.getNewDepth = function() {
	newDepth++;
	//trace("newDepth: "+newDepth);
	return newDepth;
}

_global.gBringToFront = function(clip){
	if (clip.getDepth() < newDepth){
		clip.swapDepths(getNewDepth());
	}
}

MovieClip.prototype.bringToFront = function() {
	if (this.getDepth() < newDepth){
		this.swapDepths(getNewDepth());
	}
}
