
function color_in(mc, color, w, h){
	mc.lineStyle();
	mc.beginFill(color);
	mc.moveTo(0,0);
	mc.lineTo(w, 0);
	mc.lineTo(w, h);
	mc.lineTo(0, h);
	mc.lineTo(0, 0);
	mc.endFill();
}

function draw_shadow_box(mc, w, h, bg_col, hi_col, sh_col){

	mc.lineStyle();
	mc.beginFill(bg_col);
	mc.moveTo(0,0);
	mc.lineTo(w, 0);
	mc.lineTo(w, h);
	mc.lineTo(0, h);
	mc.lineTo(0, 0);
	mc.endFill();
	
	mc.lineStyle(1, hi_col);
	mc.moveTo(0, h);
	mc.lineTo(0, 0);
	mc.lineTo(w, 0);

	mc.lineStyle(1, sh_col);
	mc.lineTo(w, h);
	mc.lineTo(0, h);
}

function set_hit_area(mc, x, y, w, h){

	mc.hit_mc_id = getNewDepth();
	mc.hit_mc = mc.createEmptyMovieClip('hit_mc_'+mc.hit_mc_id, mc.hit_mc_id);

	mc.hit_mc._visible = false;
	mc.hit_mc.lineStyle();
	mc.hit_mc.beginFill(0x000000);
	mc.hit_mc.moveTo(x,y);
	mc.hit_mc.lineTo(x+w, y);
	mc.hit_mc.lineTo(x+w, y+h);
	mc.hit_mc.lineTo(x, y+h);
	mc.hit_mc.lineTo(x, y);
	mc.hit_mc.endFill();

	mc.hitArea = mc.hit_mc;
}

function make_dragable_area(mc, x, y, w, h){

	mc.drag_mc_id = getNewDepth();
	mc.drag_mc = mc.createEmptyMovieClip('drag_mc_'+mc.drag_mc_id, mc.drag_mc_id);

	mc.drag_mc._alpha = 0;
	mc.drag_mc.lineStyle();
	mc.drag_mc.beginFill(0x000000);
	mc.drag_mc.moveTo(x,y);
	mc.drag_mc.lineTo(x+w, y);
	mc.drag_mc.lineTo(x+w, y+h);
	mc.drag_mc.lineTo(x, y+h);
	mc.drag_mc.lineTo(x, y);
	mc.drag_mc.endFill();

	mc.drag_mc.parent_mc = mc;

	mc.drag_mc.onPress = function (){
		this.parent_mc.startDrag();
		this.parent_mc.onDragStart();
	}

	mc.drag_mc.onRelease = function (){
		this.parent_mc.stopDrag();
	}
}
