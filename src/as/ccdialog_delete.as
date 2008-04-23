_global.CCDialogDelete = function() {
}

CCDialogDelete.prototype = new CCDialog();

CCDialogDelete.prototype.initialize = function(parent) {
	super.initialize(200, 100, "DELETE TILES", parent);

	this.label = new CenteredLabel().initialize(0, 30, 200, "Are you sure you want\nto delete all the pieces?", this._mc);

	this.yes_button = new ImageButton().initialize(70-13, 65, 'btn_yes', this._mc);
	this.yes_button.dialog = this;
	this.yes_button.onClick = function(){ this.dialog.onClickYes(); }

	this.no_button = new ImageButton().initialize(130-10, 65, 'btn_no', this._mc);
	this.no_button.dialog = this;
	this.no_button.onClick = function(){ this.dialog.onClickNo(); }

	return this;
}
