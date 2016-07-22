/*
Copyright Yahoo!
*/
function YahooMapsNameSpace() {

function TileXY(p_col, p_row, p_x, p_y) {
        this.m_col = p_col;
        this.m_row = p_row;
        this.m_x = p_x;
        this.m_y = p_y;
}

function TileXY() {
        this.m_col = 0;
        this.m_row = 0;
        this.m_x = 0;
        this.m_y = 0;
}

function PixelXY() {
        this.m_x = 0;
        this.m_y = 0;
}

function LatLon() {
        this.m_lat = 0;
        this.m_lon = 0;
}


function LatLon(p_lat, p_lon) {
        this.m_lat = p_lat;
        this.m_lon = p_lon;
}

var PI = 3.1415926;
var M_PER_DEGREE = 111111;
var EARTH_CIRCUM_M = M_PER_DEGREE * 360;
var RAD_PER_DEG = PI / 180.0;
var MAXLEVEL = 18;


function  Projection(p_level, clat, tilew, tileh) {
	this.init(p_level, clat, tilew, tileh);
}

Projection.prototype.init = function (p_level, clat, tilew, tileh) {
        if(p_level < 1) this.level_ = 1;
        else if(p_level > MAXLEVEL) this.level_ = MAXLEVEL;
        else this.level_ = p_level;

        this.tile_w_ = tilew;
        this.tile_h_ = tileh;

        this.status_ = 1;
        this.isok = isok;

	this.tile_width =  tile_width
	this.pixel_width =  pixel_width
	this.tile_height =  tile_height
	this.pixel_height =  pixel_height
	this.mpp =  mpp
	this.level =  level
	this.tile_size =  tile_size

	this.scaleKm = scaleKm;
	this.scaleMiles = scaleMiles;
	this.scaleFeet = scaleFeet;
	this.pix_to_tile = pix_to_tile;
}


function pix_to_tile(xp, yp) {
        v_xy = new TileXY();
	
        ypos = Math.abs(yp);

        v_xy.m_col = Math.floor(xp / this.tile_w_);
        v_xy.m_x = xp % this.tile_w_;
        v_xy.m_row = Math.floor(ypos / this.tile_h_);
        v_xy.m_y = ypos % this.tile_h_;
        if(yp < 0)
        {
            v_xy.m_row = -v_xy.m_row;
            if(y > 0)
            {
                v_xy.m_row--;
                v_xy.m_y = this.tile_h_ - v_xy.m_y;
            }
        }

	return v_xy;
}

function tile_width() {return this.ntiles_w_;}
function pixel_width() {return this.ntiles_w_ * this.tile_w_;}
function tile_height() {return this.ntiles_h_;}
function pixel_height() {return this.ntiles_h_ * this.tile_h_;}
function mpp() {return this.meters_per_pixel_;}
function level() {return this.level_;}
function tile_size() {return this.tile_w_;}
function isok() {return this.status_ == 1;}

function scaleKm(km, clat) {
     return(this.scaleMeters(km * 1000.0, clat));
}

function scaleMiles(miles, clat) {
     return(this.scaleMeters(miles * 1609.344, clat));
}

function scaleFeet(feet, clat)    {
     return(this.scaleMeters(feet / 3.282, clat));
}

function sinh(x) {
	ret = Math.exp(x);
	ret = (ret - 1 / ret) / 2;
	return ret;
}

function MercatorProjection(p_level, tilew, tileh) {
	this.init(p_level, 0.0, tilew, tileh);

        circum_px = 1 << (26 - this.level_);

        this.ntiles_w_ = circum_px / this.tile_w_;
        this.ntiles_h_ = circum_px / this.tile_h_;
        this.meters_per_pixel_ = EARTH_CIRCUM_M / circum_px;
        this.x_per_lon_ = circum_px / 360.0;

        this.ll_to_xy = ll_to_xy;
        this.xy_to_ll = xy_to_ll;
        this.type  = type ;
        this.mpp_m  = mpp_m;
        this.scaleMeters = scaleMeters;
        this.ll_to_pxy = ll_to_pxy;
        this.pxy_to_ll = pxy_to_ll;
}

MercatorProjection.prototype = new Projection();
MercatorProjection.prototype.constructor = MercatorProjection;
MercatorProjection.superclass = Projection.prototype;

function ll_to_pxy(lat, lon) {
     alat = Math.abs(lat);
     alon = lon + 180.0;

     v_pxy = new PixelXY();

     if(alat >= 90.0 || alon > 360.0 || alon < 0.0)
         return v_pxy;

     alat *= RAD_PER_DEG;

     v_pxy.m_x = parseInt(alon * this.x_per_lon_);
     ytemp = Math.log(Math.tan(alat) + 1.0 / Math.cos(alat)) / PI;
     v_pxy.m_y = parseInt(ytemp * this.pixel_height()) / 2;

     if(lat < 0) v_pxy.m_y = -v_pxy.m_y;

     this.status_ = 1;
     return v_pxy;
}


function ll_to_xy(lat, lon) {
     v_xy = new TileXY();

     v_pxy = this.ll_to_pxy(lat, lon);
     if (this.isok())
     	v_xy = this.pix_to_tile(v_pxy.m_x, v_pxy.m_y);

     return v_xy;
}


function pxy_to_ll(x_pixel, y_pixel) {
        this.status_ = 0;
        v_ll = new LatLon();

        alon = x_pixel / this.x_per_lon_;
        alat = (y_pixel / (this.pixel_height() / 2.0)) * PI;

        alat = Math.atan(sinh(alat)) / RAD_PER_DEG;

        if(alon < 0 || alon > 360.0) return v_ll;
        v_ll.m_lon = alon - 180.0;
        if(alat <= -90.0 || alat >= 90.0) return v_ll;
        v_ll.m_lat = alat;

        this.status_ = 1;

        return v_ll;
}


function xy_to_ll(col, row, x, y) {
        x_pixel = col * this.tile_w_ + x;
        y_pixel = row * this.tile_h_ + y;
        
        return(this.pxy_to_ll(x_pixel, y_pixel));       
}

function type() {return 'M';}

function mpp_m(clat) {
	return(this.meters_per_pixel_ * Math.cos(clat * RAD_PER_DEG));
}

function scaleMeters(meters, clat) {
     return(parseInt(meters / this.mpp() + 0.5));
}



function _YMap(p,type,w,h) {
/* map configuration:
	port
	type
	width
	height
*/

	if (!p) {
		if(Error) {
                throw"Map generation failed: "+Error().stack;
            	}
	}

	p.style.width = (p.style.width) ? p.style.width : '256px';
	p.style.height = (p.style.height) ? p.style.height : '256px';
	//
        p.style.position="relative";
        // p.style.position="absolute";
        p.style.overflow="hidden";
        p.style.background="#f1f1f1";
	p.style.cursor="move";
        p.style.zIndex="1";
	// offset option
        p.style.top="10px";
        p.style.left="10px";

	this.PortW = parseInt(p.style.width);
	this.PortH = parseInt(p.style.height);

	this.VuPort = p;

}

_YMap.prototype = {

	iTileSize :  256,
	aZoom : [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18],

	// combine with port object cons
	_portXY: function() {
		this.portUBX=this.iTileSize*Math.ceil(this.PortW/this.iTileSize+this.iTileInc);
		this.portUBY=this.iTileSize*Math.ceil(this.PortH/this.iTileSize+this.iTileInc);
	},

	drawZoomAndCenter: function(x,y,z) {

		if (!this.iTileInc) this.iTileInc = 1;
		this.aLatLon   = [x,y];
		this.iZoom = (z) ? z : 7;

		this._portXY();

		this.oTileCount = {
			x: Math.round(this.portUBX / this.iTileSize) + this.iTileInc, 
			y: Math.round(this.portUBY / this.iTileSize) + this.iTileInc
			};

		this._draw();

		// enable drag by default
		if (this.isDragMapEnabled()==true)
			this._dragMap();

	},
   

	enableAutoSize: function() {
	},


        addPoint: function(abc) {
            var oMidClipSize = { top:this.PortH/2, left:this.PortW/2 };
            var oIc = document.createElement('img');
            oIc.style.position = 'relative';
            oIc.id = "point"
            oIc.style.top = oMidClipSize.top + "px";
            oIc.style.left = oMidClipSize.left + "px";
            oIc.style.zIndex = 1;
            oIc.src = "http://us.i1.yimg.com/us.yimg.com/i/us/map/gr/str_ico_s.gif";
            oIc.onmouseover = function(){oIc.src = "http://us.i1.yimg.com/us.yimg.com/i/us/map/gr/str_ico_c.gif"};
            oIc.onmouseout = function(){oIc.src = "http://us.i1.yimg.com/us.yimg.com/i/us/map/gr/str_ico_s.gif"};
            oIc.onclick = function(){ oIc.src = ''; };
            this.VuPort.appendChild(oIc);
	    this.setAddPoint = 1;
        },

	removePoint: function() {
		this.setAddPoint = 0;
	},

        /*
        * Point control
        *
        * @param {Int} x: number of X directional pixels on mouse movement
        * @param {Int} y: number of Y directional pixels on mouse movement
        */
        _movePoint: function(iX,iY) {
                var s = document.getElementById('point').style;
                s.top = parseInt(s.top) + iY + "px";
                s.left = parseInt(s.left) + iX + "px";
        },



	TESTputPoint: function(x,y) {
            var oIc = document.createElement('img');
       
            oIc.style.position = 'relative';
            oIc.id = "point"
            oIc.style.top = x + "px";
            oIc.style.left = y + "px";
            oIc.style.zIndex = 1;
            oIc.src = "http://us.i1.yimg.com/us.yimg.com/i/us/map/gr/str_ico_s.gif";
                
	this.VuPort.appendChild(oIc);
	},



        setZoomLevel: function(z) {

        },

        showZoomLevel: function(z) {
        },

	getMapZoomLevel: function() {
		return this.iZoom;
	},


        addZoomHandlers: function() {
        },


        handleEvent: function(e) {
                var e = e || window.event;
                var el = e.target || e.srcElement;
                self.setZoomLevel(parseInt(el.id.substring(4))); //strip prefix
                return false;    
        },

        _enableZoomControl: function() {
        },


	addZoomControl: function(t) {
	},

	removeZoomControl: function() {

	},

        // relative scale
        _enableScaleLegend: function(z) { 
                        var o = document.createElement("img");
                        var s = o.style;
                        o.id = "scale";
                        s.position="absolute";
                        s.bottom = 0;      
                        s.margin = 2 + "px";  
                        s.zIndex = 2;
                        o.src = "http://ue.corp.yahoo.com/cyee/Maps/imgs/sc" + z + ".gif";
                        this.VuPort.appendChild(o);
        },


	//
	_dragMap: function() {
		var pj = new MercatorProjection(this.iZoom,this.iTileSize,this.iTileSize);
		var llxy = pj.ll_to_xy(this.aLatLon[0], this.aLatLon[1]);

		this.oTileOrigin = {
			x: parseInt(llxy.m_col), 
			y: parseInt(llxy.m_row)
			};
		
                var yDD = new ygDragDrop(this.VuPort.id);
                var ox = 0;
                var oy = 0;
     
                var self = this; // using inside of yDD, where "this" = yDD;

                yDD.onDrag = function() {
                        var event = ygDragDropMgr.getEvent();
                        var tmp = {
                                x: event.clientX - ox,
                                y: event.clientY - oy
                                };
                        ox = event.clientX;
                        oy = event.clientY;
                        self._pan(tmp.x, tmp.y);

			if(self.setAddPoint==1) 
				self._movePoint(tmp.x, tmp.y);
                }
                //
                yDD.startDrag = function() {
                        var event = ygDragDropMgr.getEvent();
                        ox = event.clientX;
                        oy = event.clientY;
                }
	},



	//
	disableDragMap: function() {

		this.VuPort.style.cursor = "";
		this.dragEnabled = false;
	},


	//
	enableDragMap: function() {

		this.VuPort.style.cursor = "move";
		this.dragEnabled = true;
	},

	//
	isDragMapEnabled: function() {
		return ((this.dragEnabled=="undefined"||this.dragEnabled==false)? false : true);
	},



	// on redraw
	_clearView: function() {
		var o;
		if (this.VuPort.hasChildNodes())
		{
			var i = this.VuPort.childNodes.length - 1;
			while (this.VuPort.childNodes.length > 0) 
			{  
		 		o = this.VuPort.childNodes[i];
		 		this.VuPort.removeChild(o);
				i--;
			}
		}
	},
   
   
	_draw: function() {
      		this._clearView();
		
		var pj = new MercatorProjection(this.iZoom,this.iTileSize,this.iTileSize);
		var xy = pj.ll_to_xy(this.aLatLon[0], this.aLatLon[1]);
		
		var oMidbyPx = {
			left: this.PortW/2 - xy.m_x, 
			top: this.PortH/2 - (this.iTileSize - xy.m_y)
			};

		var oMidbyTile = {
			row: Math.floor((this.oTileCount.x)/2), 
			col: Math.floor((this.oTileCount.y)/2)
			};
	
		var iTileRow =  xy.m_row - oMidbyTile.row;
		var iTileCol =  xy.m_col - oMidbyTile.col;


		var iTopPx =  oMidbyPx.top + oMidbyTile.row * this.iTileSize;
		var iLeftPx = oMidbyPx.left - oMidbyTile.col * this.iTileSize;
		
		var tmp = {
			top: iTopPx, 
			left: iLeftPx
			};
		
		for (iX=0; iX<this.oTileCount.x+1; iX++)
		{
				tmp.top = iTopPx;
				for (iY=0; iY<this.oTileCount.y+1; iY++)
				{
					this._generateTile(tmp.top, tmp.left, iTileCol + iX, iTileRow + iY);
					tmp.top -= this.iTileSize;
				}
				tmp.left += this.iTileSize;
		}
		
		this._enableLogo();
		this._enableScaleLegend(this.iZoom);

		if (this._showZoomControl == "true") {
			this._enableZoomControl();
		}

	}, 

	_generateTile: function(posT,posL,iTileX,iTileY) {
		// var t = document.createElement("IMG");
		var t = new Image(this.iTileSize,this.iTileSize);
		var s = t.style;
		s.position = "absolute";
		s.top = posT + "px";
		s.left = posL + "px";
		s.zIndex = 1;
		t.src = this._getUrl(iTileX, iTileY, this.iZoom);
				//t.border = 1;
		// t.onload = function(){ this.style.visibility = "visible"; };
		t.xCoord = iTileX;
		t.yCoord = iTileY;
		this.VuPort.appendChild(t);
	},
	
	_enableLogo: function() {
   			var o = document.createElement("img");
			var s = o.style;
			o.id = "ylogo";
			s.position = "absolute";
			s.margin = 2 + "px";
			s.zIndex = 2;
			o.src = "http://us.i1.yimg.com/us.yimg.com/i/us/map/gr/yahoo.gif";
			this.VuPort.appendChild(o);
	},
	

	_getUrl: function(x,y,z) {
		//var prd = 'http://png.maps.yimg.com/png?l=tile.maps.scd.yahoo.com&';
		var prd = 'http://beast.corp.yahoo.com/maps/gt?';
		//var prd = 'http://png.maps.yimg.com/png?l=t4.maps.scd.yahoo.com&';
		return prd + "x=" + x + "&y=" + y + "&z=" + z;
	},
   

	_getTileXpan: function(oEl) { 
		return (oEl.style.left) ? parseInt(oEl.style.left) : 0;
	},
   	
	_getTileYpan: function(oEl) { 
		return (oEl.style.top) ? parseInt(oEl.style.top) : 0; 
	},
   	
	
	_updateXYpan: function(iX, iY) {
			this.pix2deg = this.iTileInc / this.iTileSize; //used in _pan
			this.oTileOrigin = {
				x: this.oTileOrigin.x -= iX * this.pix2deg , 
				y: this.oTileOrigin.y += iY * this.pix2deg
				};
	},
	
	_updateLLpan: function() {
			var pj = new MercatorProjection(this.iZoom,this.iTileSize,this.iTileSize);
			var xyll = pj.xy_to_ll(this.oTileOrigin.x, this.oTileOrigin.y, v_xy.m_x, v_xy.m_y);
			this.aLatLon = [xyll.m_lat, xyll.m_lon];
	},
   
	_pan: function(iX, iY) {
		var self = this;
		self._updateXYpan(iX, iY);
		self._updateLLpan(); //assumes _updateXYpan has been initialized already
		 
	   	for (var i = 0; i< this.VuPort.childNodes.length; i++) 
		{
	   		var t = this.VuPort.childNodes[i];
			var s = t.style;
			if(t.id == "ylogo"){
				return false;
			}
	   		var tmp = {
				x: this._getTileXpan(t) + iX, 
				y: this._getTileYpan(t) + iY
				};
			
	   		s.left = Math.round(tmp.x) + "px";
	   		s.top  = Math.round(tmp.y) + "px";
	   		// s.visibility = "hidden";
			
	   		var bRender = true;

	   		if ( tmp.x >= this.PortW )  // <- move
			{
	   			t.xCoord -= this.oTileCount.x * this.iTileInc;
	   			s.left = Math.round(tmp.x - this.portUBX - this.iTileSize) + "px";
	   		} 
			else if ( tmp.x <= -this.iTileSize ) // -> move (fills in on the right)
			{
	   			t.xCoord += this.oTileCount.x * this.iTileInc;
	   			s.left = Math.round(tmp.x + this.portUBX + this.iTileSize) + "px";
	   		}
	   		
	
			if ( tmp.y >= this.PortH )  // /|\ move
			{
	   			t.yCoord += ((this.oTileCount.y) * this.iTileInc);
	   			s.top = Math.round(tmp.y - this.portUBY - this.iTileSize) + "px";
	   		}
			else if ( tmp.y <= -this.iTileSize )  // \|/ move (fills in on the bottom)
			{
	   			t.yCoord -= ((this.oTileCount.y) * this.iTileInc);
	   			s.top = Math.round(tmp.y + this.portUBY + this.iTileSize) + "px";
	   		}
			
	   		if (bRender) {
				// (new Image(this.iTileSize,this.iTileSize)).src
				 t.src = this._getUrl(t.xCoord, t.yCoord, this.iZoom);
				//t.border = 1;
			bRender = false;
			}
	   	}
	}
	}




	function ppr(inp) {
   	var oDebug = document.getElementById('debug');
   	if (!oDebug) {
      	oDebug = document.createElement('div');
      	oDebug.id = 'debug';
      	document.body.appendChild(oDebug); }     
   	var t = typeof inp;
   	oDebug.innerHTML +=  '<pre>' ;
   	if (t == 'object' || t == 'array') {
   	for(var o in inp) {
      		oDebug.innerHTML +=  o ;
   	   	oDebug.innerHTML +=  ' :: ' + inp[o] ;
   	   	oDebug.innerHTML +=  "<br><br>";
   	} } else {
      	oDebug.innerHTML +=  inp ;
	}
      	oDebug.innerHTML +=  '</pre>' ;
	}



	function Exporter(o) {
	var _E = o||window;
	_E.YMap = _YMap;
	_E.ppr  = ppr;
	}


	Exporter();
};
YahooMapsNameSpace();
