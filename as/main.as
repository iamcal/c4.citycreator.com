#include "as/onload.as"
#include "as/core.as"
#include "as/painting.as"

#include "as/loader.as"

#include "as/mainframe.as"
#include "as/button.as"
#include "as/canvas.as"

//#include "as/pieces.as"

#include "as/tile_manager.as"
#include "as/tile_set.as"
#include "as/tile_set_tab.as"
#include "as/tile_source.as"
#include "as/tile.as"



var gMainFrame;
var gTileManager;

function startup(){

	//
	// first, create the master frame
	//

	//gMainFrame = new MainFrame().initialize(0, 0, 740, 448);


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

		trace('start loading pieces');
		gTileManager = new TileManager().initialize(this.childNodes[0]);
		trace('end loading pieces');
		trace(gLoadingManager);

		gLoadingManager.onLoaded = onPiecesReady;
		gLoadingManager.start_checking();

		trace('end start checking');

	}else{
		trace("failed to load :(");
	}
}

function onPiecesReady(){

	//
	// we're ready to rock!
	//

	gMainFrame = new MainFrame().initialize(0, 0, 740, 448);

	var button_parent = gMainFrame.GetCanvasMc();

	var b2 = new MyButton().initialize(10, 40, 75, 25, "hide canvas", button_parent);
	b2.onClick = function() { gMainFrame.getCanvasMc()._visible = false; }

	var b3 = new MyButton().initialize(10, 70, 75, 25, "set 0", button_parent);
	b3.onClick = function() { gTileManager.showSet(0); }

	var b4 = new MyButton().initialize(10, 100, 75, 25, "set 1", button_parent);
	b4.onClick = function() { gTileManager.showSet(1); }


	trace("onPiecesReady()");
	gTileManager.initTiles();
	gMainFrame.bringCanvasForward();
}

_global.library_loaded = function(library){
	
	trace('library_loaded()');
	//trace(library);
	//trace(library.piece_list);
	//trace(library.piece_list[0]);

	//var t = new TileSource().initialize(library.piece_list[0]);
}

function try_load_library(){

	var id = getNewDepth();
	var mc = _root.createEmptyMovieClip('loading_lib_mc' + id, id);

	mc.loadMovie('test_lib.swf');
}

function load_local(){
	m = new TileManager().initialize();
	m.addTile('piece_1');
	m.addTile('piece_1');
	m.addTile('piece_1');
	m.addTile('piece_1');
	m.addTile('piece_1');
	m.addTile('piece_1');
	m.initTiles();
}

