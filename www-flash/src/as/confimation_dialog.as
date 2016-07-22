_global.ConfirmationDialog = function() {
	this.id = 0;
}

ConfirmationDialog.prototype.initialize = function(question, obj, method_yes, method_no) {

	this.question = question;
	this.obj = obj;
	this.method_yes = method_yes;
	this.method_no = method_no;

	this.id = getNewDepth();
	this._mc = _root.createEmptyMovieClip('dialog_mc_' + this.id, this.id);
	this._mc._x = 0;
	this._mc._y = 0;
	this._mc._alpha = 50;

	this.w = _root._width;
	this.h = _root._height;

	color_in(this._mc, 0x000000, this.w, this.h);

	this.window = new ConfirmationDialogWindow().initialize(this);

	return this;
}

_global.ConfirmationDialogWindow = function() {
	this.id = 0;
}

ConfirmationDialogWindow.prototype.initialize = function(dialog) {
	this.dialog = dialog;

	this.w = 300;
	this.h = 100;

	this.id = getNewDepth();
	this._mc = _root.createEmptyMovieClip('dialog_window_mc_' + this.id, this.id);
	this._mc._x = Math.round((this.dialog.w/2) - (this.w/2));
	this._mc._y = Math.round((this.dialog.h/2) - (this.h/2));

	color_in(this._mc, 0xffffff, this.w, this.h);

	return this;
}
