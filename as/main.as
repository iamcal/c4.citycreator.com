#include "as/onload.as"
#include "as/core.as"
#include "as/painting.as"

#include "as/loader.as"

#include "as/mainframe.as"
#include "as/button.as"
#include "as/canvas.as"

#include "as/tile_manager.as"
#include "as/tile_set.as"
#include "as/tile_set_tab.as"
#include "as/tile_source.as"
#include "as/tile.as"


var gBgColor;
var gMainFrame;
var gTileManager;

function startup(){

	Stage.showMenu = false;
	Stage.scaleMode = 'NoScale';
	Stage.align = 'TL';


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
	xmlDoc.load("pieces.xml");
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

	gMainFrame = new MainFrame().initialize(0, 0, 740, 448, gBgColor);

	//
	// create some debug buttons
	//

	var button_parent = gMainFrame.GetCanvasMc();

	var b2 = new MyButton().initialize(10, 40, 75, 25, "delete all", button_parent);
	b2.onClick = function() { gTileManager.deleteAll(); }


	//
	// ready the tilesets
	//

	gTileManager.initTiles();
	gMainFrame.bringCanvasForward();
}
