//
// City Creator
// (C)2002 Cal Henderson / Denise Wilton
//

var trash_left   = 656 - 257;
var trash_top    = 387 - 15;
var trash_width  = 66;
var trash_height = 49;

var piece_count = 0;
var loaded_count = 0;

var max_cookie_len = 4000; //we're allowed 4k (4096) but that includes the cookie name

var top_count = 0;
var piece_index = new Array();
var preloads = new Array();

var shadow_layer = null;

function init(){
	document.body.onmousemove = mouse_move;
	document.body.onmouseup   = mouse_up;

	tab(first_tab);

	shadow_layer = document.getElementById('shadow');

	get_elm("loading").style.display = 'none';
	get_elm("canvasinner").style.display = 'block';

	if ((readCookie(cookie_hello) == null)){
		show_elm(get_elm('instructions'));
	}
	saveCookie(cookie_hello, 'hello', 365);
}


function add_piece(uid, palette, id, old_palette, old_id, width, height, ox, oy, src){

	var row = Math.floor(id / 4);
	var col = id % 4;

	var x = Math.floor((58 * col) + (57 / 2) - (width / 2));
	var y = Math.floor((67 * row) + (66 / 2) - (height / 2));

	var img = new Image();
	img.onload = PieceLoaded;
	img.src = "blocks/"+src;
	preloads[preloads.length] = img;

	var elm = document.createElement('DIV');
	elm.style.position = 'absolute';
	elm.style.left = x;
	elm.style.top = y;
	elm.style.width = width;
	elm.style.height = height;
	elm.style.backgroundImage = "url(blocks/"+src+")";
	elm.style.fontSize = '1px';
	elm.style.zIndex = 2;

	elm.id = 'piece_'+uid;
	elm.onmousedown = click_wrapper;

	elm.pos_x = x;
	elm.pos_y = y;
	elm.size_x = width;
	elm.size_y = height;
	elm.id_palette = palette;
	elm.id_id = id;
	elm.uid = uid;
	elm.img_src = src;
	elm.offset_x = ox;
	elm.offset_y = oy;

	add_child(get_elm("palette"+palette), elm);

	piece_index[old_palette+"_"+old_id] = elm;
}

function add_piece_holder(uid, palette, id, old_palette, old_id){
	var width = 51;
	var height = 57;

	var row = Math.floor(id / 4);
	var col = id % 4;

	var x = Math.floor((58 * col) + (57 / 2) - (width / 2));
	var y = Math.floor((67 * row) + (66 / 2) - (height / 2));

	var elm = document.createElement('DIV');
	elm.style.position = 'absolute';
	elm.style.left = x;
	elm.style.top = y;
	elm.style.width = width;
	elm.style.height = height;
	elm.style.backgroundImage = "url(blocks/hold.gif)";
	elm.style.fontSize = '1px';
	elm.style.zIndex = 2;

	elm.onclick = function(){ alert('To vote for a new building, click on the vote tab along the top.'); }

	add_child(get_elm("palette"+palette), elm);
}

function click_wrapper(e){
	make_piece(e, this.uid);
}

function add_bg(id, image, color){
	if (image){
		bgs[id] = "url("+image+")";
	}else{
		bgs[id] = color;
	}
}

function tab(id){
	if (current_tab >= 0){
		show_elm(get_elm("tab"+current_tab+"off"));
		hide_elm(get_elm("tab"+current_tab+"on"));
		hide_elm(get_elm("palette"+current_tab));
	}
	hide_elm(get_elm("tab"+id+"off"));
	show_elm(get_elm("tab"+id+"on"));
	show_elm(get_elm("palette"+id));
	current_tab = id;
}

function get_elm(name){
	return document.getElementById(name);
}

function show_elm(elm){
	if (!elm){return;}
	elm.style.visibility = 'visible';
}

function hide_elm(elm){
	if (!elm){return;}
	elm.style.visibility = 'hidden';
}

function make_piece(e, uid){
	var x = (e)? e.pageX : event.x + document.body.scrollLeft;
	var y = (e)? e.pageY : event.y + document.body.scrollTop;

	var p_elm = get_elm('piece_'+uid);

	var p_x = p_elm.pos_x - 243;
	var p_y = p_elm.pos_y + 32;

	drag_offset_x = x - p_x;
	drag_offset_y = y - p_y;

	var elm = create_elm(p_x, p_y, p_elm.size_x, p_elm.size_y, p_elm.offset_x, p_elm.offset_y, get_max_z() + 1, p_elm.img_src, p_elm.uid, p_elm.id_palette, p_elm.id_id);

	currently_dragging = 1;
	drag_elm = elm;
	toggle_grid();

	return false;
}

function move_piece(e, palette, id, elm){
	var x = (e)? e.pageX : event.x + document.body.scrollLeft;
	var y = (e)? e.pageY : event.y + document.body.scrollTop;

	drag_offset_x = x - elm.pos_x;
	drag_offset_y = y - elm.pos_y;

	// reorder elements
	var this_z = elm.style.zIndex;
	for(var i=0; i<pieces.length; i++){
		if (pieces[i].style.zIndex > this_z){
			pieces[i].style.zIndex = pieces[i].style.zIndex - 1;
		}
	}
	elm.style.zIndex = get_max_z() + 1;

	currently_dragging = 1;
	drag_elm = elm;
	toggle_grid();

	return false;
}

function add_child(parent, child){
	parent.appendChild(child);
}

function mouse_move(e){
	var x = (e)? e.pageX : event.x + document.body.scrollLeft;
	var y = (e)? e.pageY : event.y + document.body.scrollTop;

	if (!currently_dragging) return;
	move_elm(drag_elm, x-drag_offset_x, y-drag_offset_y);
	move_shadow();

	return false;
}

function move_elm(elm, x, y){
	elm.style.left = x + 'px';
	elm.style.top = y + 'px';
	elm.pos_x = x;
	elm.pos_y = y;
}

function mouse_up(){
	if (!currently_dragging) return;
	currently_dragging = 0;
	toggle_grid();

	//snap the current piece
	var x = grid_w * Math.round((drag_elm.pos_x - drag_elm.offset_x) / grid_w);
	var y = grid_h * Math.round((drag_elm.pos_y - drag_elm.offset_y) / grid_h);

	move_elm(drag_elm, x + drag_elm.offset_x, y + drag_elm.offset_y);

	if (intersect(drag_elm.pos_x, drag_elm.pos_y, drag_elm.size_x, drag_elm.size_y, trash_left, trash_top, trash_width, trash_height)){
		delete_elm(drag_elm);
	}

	update_pieces();
}

function get_max_z(){
	var max_z = 0;
	for(var i=0; i<pieces.length; i++){
		if (pieces[i].style.zIndex > max_z){
			max_z = parseInt(pieces[i].style.zIndex);
		}
	}
	return max_z;
}

function bg(id){
	bg_string = bgs[id];
	bg_id = id;
	// we need to clear this first, else the image doesn't unload on safari
	// when we need to switch to a plain color
	get_elm('boardback').style.backgroundImage = 'none';
	get_elm('boardback').style.background = bg_string;
	update_pieces();
}

function bg_nosave(id){
	bg_string = bgs[id];
	bg_id = id;
	get_elm('boardback').style.background = bg_string;
}

function intersect(x1,y1,w1,h1,x2,y2,w2,h2){
	if (x2 > x1+w1) return false;
	if (x2+w2 < x1) return false;
	if (y2 > y1+h1) return false;
	if (y2+h2 < y1) return false;
	return true;
}

function delete_all(){
	if (!window.confirm('Are you sure you want to delete all pieces?')) return;

	while(pieces.length > 0){
		delete_elm(pieces[0]);
	}
	update_pieces();
}

function delete_elm(elm){
	var index = 0;
	var last_index = pieces.length-1;
	for(var i=0; i<pieces.length; i++){
		if (pieces[i] == elm){
			index = i;
		}
	}
	pieces[index] = pieces[last_index];
	pieces[last_index] = null;
	pieces.length = last_index;

	elm.style.visibility = 'hidden';
	elm.onmousedown = function(){};
}

function update_pieces(){
	// cut up the big cookie so we don't break the browser ;)
	var piece_data = serialize_all();
	var cookie_count = 0;
	while(piece_data.length > 0){
		cookie_count++;
		saveCookie(cookie_prefix+'pieces_'+cookie_count, piece_data.substr(0,max_cookie_len), 365);
		piece_data = piece_data.substr(max_cookie_len);
	}
	saveCookie(cookie_prefix+'piece_count', cookie_count, 365);
	saveCookie(cookie_prefix+'bg_id', bg_id, 365);
}

function load_pieces(){
	if (readCookie(cookie_prefix_old+'bg_id') > 0){

		//alert("old format cookies detected - removing and converting");
		load_pieces_guts(cookie_prefix_old);
		update_pieces();

		// kill old cookies
		var count = readCookie(cookie_prefix_old+'piece_count');
		if (count != null){
			for(i=1; i<=count; i++){
				saveCookie(cookie_prefix_old+'pieces_'+i, 0, 0);
			}
		}
		saveCookie(cookie_prefix_old+'bg_id', 0, 0);
		saveCookie(cookie_prefix_old+'piece_count', 0, 0);
		
	}else{
		load_pieces_guts(cookie_prefix);
	}
}

function load_pieces_guts(prefix){

	var temp = readCookie(prefix+'bg_id');
	if (temp != null){
		bg_nosave(temp);
	}

	var count = readCookie(prefix+'piece_count');

	var temp = null;
	if (count != null){
		temp = '';
		for(i=1; i<=count; i++){
			temp += readCookie(prefix+'pieces_'+i);
		}
	}

	if ((temp != null) && (temp != '')){
		unserialize_all(temp);
	}
}

function unserialize_all(data){
	//alert('unserialize_all:'+data);
	var bits = data.split(',');
	for(var i=0; i<bits.length; i++){
		unserialize_elm(bits[i]);
	}

	// we call this because the saved city might have ended up with crazy indexes after
	// a fair bit of work :)
	reduce_zindexes();
}

function serialize_all(){
	var bits = new Array();
	for(var i=0; i<pieces.length; i++){
		bits[bits.length] = serialize_elm(pieces[i]);
	}
	return bits.join(',');
}

function serialize_elm(elm){
	return elm.uid + '|' + elm.pos_x + '|' + elm.pos_y + '|' + elm.style.zIndex;
}

function unserialize_elm(data){
	var bits = data.split('|');
	if (bits.length == 5){
		// old format
		var p_elm = find_elm_old(bits[0], bits[1]);
		if (p_elm == null){ return; }
		var x = bits[2];
		var y = bits[3];
		var z = bits[4];
	}else{
		// new format
		var p_elm = get_elm('piece_'+bits[0]);
		var x = bits[1];
		var y = bits[2];
		var z = bits[3];
	}
	create_elm(x, y, p_elm.size_x, p_elm.size_y, p_elm.offset_x, p_elm.offset_y, z, p_elm.img_src, p_elm.uid, p_elm.palette, p_elm.id);
}

function find_elm_old(palette, id){
	return piece_index[palette+"_"+id];
}

function create_elm(x, y, w, h, ox, oy, z, src, uid, palette, id){

	var elm = document.createElement('DIV');
	elm.style.position = 'absolute';
	elm.style.left = x;
	elm.style.top = y;
	elm.style.width = w;
	elm.style.height = h;
	elm.style.zIndex = z;
	elm.style.fontSize = '1px';

	elm.style.backgroundImage = "url(blocks/"+src+")";

	elm.pos_x = x;
	elm.pos_y = y;
	elm.size_x = w;
	elm.size_y = h;
	elm.uid = uid;
	elm.id_palette = palette;
	elm.id_id = id;
	elm.offset_x = ox;
	elm.offset_y = oy;

	elm.onmousedown = function(e){move_piece(e, palette, id, elm);}

	add_child(get_elm("board"), elm);
	pieces[pieces.length] = elm;

	return elm;
}

function send_card(){
	var f = document.sendform;
	f.city_pieces.value = serialize_all();
	f.city_bg.value = bg_id;
	f.submit();
}

function show_top(elm_name){
	var elm = get_elm(elm_name);
	top_count++;
	elm.style.zIndex = 1000 + top_count;
	show_elm(elm);
}

function toggle_grid(){
	var value = (currently_dragging)?'visible':'hidden';
	//var elm = document.getElementById('grid');
	//elm.style.visibility = value;
	shadow_layer.style.visibility = value;

	if (currently_dragging){
		shadow_layer.style.zIndex = drag_elm.style.zIndex;
		shadow_layer.style.width  = drag_elm.style.width;
		shadow_layer.style.height = drag_elm.style.height;
		move_shadow();
	}
}

function move_shadow(){
	//snap the current piece
	var x = grid_w * Math.round((drag_elm.pos_x - drag_elm.offset_x) / grid_w);
	var y = grid_h * Math.round((drag_elm.pos_y - drag_elm.offset_y) / grid_h);

	shadow_layer.style.left = x + drag_elm.offset_x;
	shadow_layer.style.top  = y + drag_elm.offset_y;
}

function PieceLoaded(){
	loaded_count++;
	var percent = Math.floor((loaded_count / piece_count) * 100);
	var elm = get_elm("loadprogress");
	elm.style.width = percent+'%';
}

function doIntSort(a, b){
	if (a == b) return 0;
	return a > b ? 1 : -1;
}

function reduce_zindexes(){

	// first, get an array of all of the current zIndexes

	var zIndexes = new Array();
	var uniq = {};

	for(var i=0; i<pieces.length; i++){
		var z = parseInt(pieces[i].style.zIndex);
		if (uniq[z] != 1){
			uniq[z] = 1;
			zIndexes[zIndexes.length] = z;
		}
	}


	// now sort them into order

	var sortedZ = zIndexes.sort(doIntSort);


	// new create a new hash of values to translate to

	var newZ = {};
	var c = 1;

	for(var i=0; i<sortedZ.length; i++){
		var z = sortedZ[i];
		newZ[z] = c++;
	}

	// reset all the zIndexes

	for(var i=0; i<pieces.length; i++){
		var z = parseInt(pieces[i].style.zIndex);

		pieces[i].style.zIndex = newZ[z];
	}
}