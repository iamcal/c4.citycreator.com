#include "as/onload.as"
#include "as/core.as"
#include "as/painting.as"

#include "as/loader.as"
#include "as/loader_2.as"

#include "as/mainframe.as"
#include "as/button.as"
#include "as/image_button.as"
#include "as/canvas.as"
#include "as/dialog.as"
#include "as/progress_bar.as"
#include "as/label.as"

#include "as/tile_manager.as"
#include "as/tile_set.as"
#include "as/tile_set_tab.as"
#include "as/tile_source.as"
#include "as/tile.as"


var gBgColor;
var gMainFrame;
var gTileManager;
var gAboutDialog;
var gInstructionsDialog;
var gLoadingButton;
var gLoadingLabel;
var gLoadingProgress;
var gLoadingTimer;

function startup(){

	Stage.showMenu = false;
	Stage.scaleMode = 'NoScale';
	Stage.align = 'TL';

	_root._highquality = 0;

	//
	// first, create the master frame
	//

	gBgColor = 0x000000;

	//
	// loading progress
	//

	gLoadingProgress = new ProgressBar().initialize(10, 60, 720, 20);
	gLoadingLabel = new MyLabel().initialize(10, 30, "Loading Application...");

	//
	// set a timer running to check when the app itself is loaded
	//

	gLoadingTimer = setInterval(check_app_loaded, 100);
}


function check_app_loaded(){

	if (_root.getBytesLoaded() < _root.getBytesTotal()){
		return;
	}

	clearInterval(gLoadingTimer);
	gLoadingLabel.setCaption('Loading City XML...');


	//
	// load the pieces xml
	//

	var xmlDoc;

	xmlDoc = new XML();
	xmlDoc.ignoreWhite = true;
	xmlDoc.onLoad = onPiecesLoaded;
	xmlDoc.load("city_1.xml");
}

function onPiecesLoaded(success){

	if (success){

		gLoadingLabel.setCaption('Loading City Components...');

		gTileManager = new TileManager().initialize(this.childNodes[0]);

		gLoadingManager.onLoaded = onPiecesReady;
		gLoadingManager.onLoading = onPiecesLoading;
		gLoadingManager.start_checking();

	}else{
		trace("Failed to load City XML :(");
	}
}

function onPiecesLoading(){

	var percent = 100 * gLoadingManager.clips_loaded / gLoadingManager.clips_total;

	gLoadingProgress.setProgress(percent);

	percent = Math.round(percent);

	gLoadingButton.setCaption(percent+'%');
}

function onPiecesReady(){

	//
	// we're ready to rock!
	//
	// create the frame and buttons
	//

	gMainFrame = new MainFrame().initialize(0, 0, 740, 448, gBgColor);

	var b1 = new ImageButton().initialize(416, 403, 'btn_save', gMainFrame.getMc());
	b1.onClick = function(){ button_save(); }

	var b2 = new ImageButton().initialize(355, 403, 'btn_about', gMainFrame.getMc());
	b2.onClick = function(){ button_about(); }

	var b3 = new ImageButton().initialize(287, 403, 'btn_instructions', gMainFrame.getMc());
	b3.onClick = function(){ button_instructions(); }

	var b4 = new ImageButton().initialize(491, 403, 'btn_delete', gMainFrame.getMc());
	b4.onClick = function(){ button_delete_all(); }

	//
	// create dialogs
	//

	gAboutDialog = new Dialog().initialize(10, 266, 'dialog_about');
	gInstructionsDialog = new Dialog().initialize(10, 262, 'dialog_instructions');


	//
	// ready the tilesets
	//

	gTileManager.initTiles();
	gMainFrame.bringCanvasForward();
}

/////////////////////////////////////////////////////////////////////////////
//
// Button Handlers
//

function button_delete_all() {
	trace('are you sure?');
	gTileManager.deleteAll();
}

function button_instructions() {
	trace('instructions');
	gInstructionsDialog.showAt(298, 54);
}

function button_about() {
	trace('about');
	gAboutDialog.showAt(396, 86);
}

function button_save() {
	trace('save');
}

/////////////////////////////////////////////////////////////////////////////
