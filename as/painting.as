
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
