
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
#include "as/confimation_dialog.as"
#include "as/ccdialog.as"
#include "as/ccdialog_delete.as"

#include "as/bg_manager.as"
#include "as/tab_manager.as"

#include "as/tile_manager.as"
#include "as/tile_set.as"
#include "as/tile_set_tab.as"
#include "as/tile_source.as"
#include "as/tile.as"


var gBgColor;
var gMainFrame;
var gTileManager;
var gBgManager;
var gTabManager;
var gAboutDialog;
var gInstructionsDialog;
var gDeleteDialog;
var gLoadingButton;
var gLoadingLabel;
var gLoadingProgress;
var gLoadingTimer;


_global.startup = function(){

	_global.clientV = 8;
	_global.traceAllXML = true;

	System.security.allowDomain("citycreator.com");
	System.security.allowDomain("flash.citycreator.com");

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

_global.check_app_loaded = function (){

	if (_root.getBytesLoaded() < _root.getBytesTotal()){
		return;
	}

	clearInterval(gLoadingTimer);


	//
	// where is the pieces xml?
	//

	var xml_src = "city_1.xml";
	if (_root.thexml != undefined){
		xml_src = _root.thexml;
	}

	gLoadingLabel.setCaption('Loading City XML...');


	//
	// load the pieces xml
	//

	var xmlDoc = new XML();
	xmlDoc.ignoreWhite = true;
	xmlDoc.onLoad = onPiecesLoaded;
	xmlDoc.load(xml_src);
	
}

_global.onPiecesLoaded = function (success){

	if (success){

		gLoadingLabel.setCaption('Loading City Components...');

		for (var i=0; i<this.childNodes.length; i++) {
			var child = this.childNodes[i];

			if (child.nodeName == 'pieces'){
				gTileManager = new TileManager().initialize(child);
			}

			if (child.nodeName == 'backgrounds'){
				gBgManager = new BgManager().initialize(child);
			}

			if (child.nodeName == 'tabs'){
				gTabManager = new TabManager().initialize(child);
			}
		}		

		gLoadingManager.onLoaded = onPiecesReady;
		gLoadingManager.onLoading = onPiecesLoading;
		gLoadingManager.start_checking();

	}else{
		gLoadingLabel.setCaption("Failed to load City XML :(");
	}
}

_global.onPiecesLoading = function(){

	var percent = 100 * gLoadingManager.clips_loaded / gLoadingManager.clips_total;

	gLoadingProgress.setProgress(percent);

	percent = Math.round(percent);

	gLoadingButton.setCaption(percent+'%');
}

_global.onPiecesReady = function(){

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

	gDeleteDialog = new CCDialogDelete().initialize();
	gDeleteDialog.onClickYes = function(){ this.hide(); gTileManager.deleteAll(); }
	gDeleteDialog.onClickNo = function(){ this.hide(); }

	//
	// ready the tilesets
	//

	gBgManager.onBgsLoaded = init_bg;

	gTileManager.initTiles();
	gBgManager.initBgs();
	gTabManager.initTabs();
	gMainFrame.bringCanvasForward();
}

function init_bg(){
	// this is zero indexed!
	gBgManager.setBg(0);
	gMainFrame.bringCanvasForward();
}

/////////////////////////////////////////////////////////////////////////////
//
// Button Handlers
//

_global.button_delete_all = function() {
	//trace('are you sure?');
	gDeleteDialog.showAt(391, 160);
}

_global.button_instructions = function() {
	//trace('instructions');
	gInstructionsDialog.showAt(298, 54);
}

_global.button_about = function() {
	//trace('about');
	gAboutDialog.showAt(396, 86);
}

_global.button_save = function() {
	trace('save');
}

/////////////////////////////////////////////////////////////////////////////

