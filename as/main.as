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

#include "as/tile_manager.as"
#include "as/tile_set.as"
#include "as/tile_set_tab.as"
#include "as/tile_source.as"
#include "as/tile.as"


var gBgColor;
var gMainFrame;
var gTileManager;
var gAboutDialog;

function startup(){

	Stage.showMenu = false;
	Stage.scaleMode = 'NoScale';
	Stage.align = 'TL';

	_root._highquality = 0;

	gAboutDialog = new Dialog().initialize(0, 0, 'dialog_about');
	gAboutDialog.showAt(100,100);


	//
	// first, create the master frame
	//

	gBgColor = 0x000000;


	//
	// then some buttons
	//

	var button_parent = _root;

	b = new MyButton().initialize(10, 10, 75, 25, "load xml", button_parent);
	b.onClick = function() {
		this.setCaption('Loading...');
		load_city_xml();
		delete this.onClick;
	}
}


function load_city_xml(){

	//
	// load the pieces xml
	//

	var xmlDoc;

	xmlDoc = new XML();
	xmlDoc.ignoreWhite = true;
	xmlDoc.onLoad = onPiecesLoaded;
	xmlDoc.load("city_1.xml");
	//xmlDoc.load("pieces.xml");
}

function onPiecesLoaded(success){

	if (success){

		gTileManager = new TileManager().initialize(this.childNodes[0]);

		gLoadingManager.onLoaded = onPiecesReady;
		gLoadingManager.start_checking();

	}else{
		trace("failed to load :(");
	}
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
}

function button_about() {
	trace('about');
}

function button_save() {
	trace('save');
}

/////////////////////////////////////////////////////////////////////////////
