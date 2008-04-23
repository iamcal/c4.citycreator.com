<?
	include("../init.txt");

	#
	# pieces
	#

	$result = mysql_query("SELECT * FROM citycreator_pieces");
	while ($piece_row = mysql_fetch_array($result)){

		$png_filename = "../blocks/$piece_row[city_id]_png/".str_replace('gif','png',$piece_row[image]);
		$swf_filename = "./$piece_row[city_id]_".str_replace('gif','swf',$piece_row[image]);

	#	png_2_swf($png_filename, $swf_filename, $piece_row[w], $piece_row[h]);
	}

	#
	# backgrounds
	#

	$result = mysql_query("SELECT * FROM citycreator_bgs");
	while($bg_row = mysql_fetch_array($result)){

		if ($bg_row[thumb]){

			#
			# thumb
			#

			$png_filename = "../bgs/$bg_row[city_id]_png/".str_replace('gif','png',$bg_row[thumb]);
			$swf_filename = "./bg_thumb_$bg_row[city_id]_$bg_row[order_id].swf";

			png_2_swf($png_filename, $swf_filename, 25, 24);

			#
			# full sizes
			#

			$png_filename = "../bgs/$bg_row[city_id]_png/".str_replace('gif','png',$bg_row[bg_image]);
			$swf_filename = "./bg_full_$bg_row[city_id]_$bg_row[order_id].swf";

			png_2_swf_tiled($png_filename, $swf_filename, 469, 364);

		}else{

			$swf_filename = "./bg_thumb_$bg_row[city_id]_$bg_row[order_id].swf";

			solid_png($swf_filename, 21, 12, $bg_row[bg_color]);


			$swf_filename = "./bg_full_$bg_row[city_id]_$bg_row[order_id].swf";

			solid_png($swf_filename, 469, 364, $bg_row[bg_color]);
		}
	}

	#
	# city logos and tabs
	#

	$result = mysql_query("SELECT * FROM citycreator_cities");
	while ($city_row = mysql_fetch_array($result)){

		#
		# truck
		#

		$png_filename = "../images/frame/truck_$city_row[id].png";
		$swf_filename = "./truck_$city_row[id].swf";

		png_2_swf($png_filename, $swf_filename, 66, 49);


		#
		# logo
		#

		$png_filename = "../images/frame/logo_$city_row[id].png";
		$swf_filename = "./logo_$city_row[id].swf";

		png_2_swf($png_filename, $swf_filename, 231, 45);

	}


	echo "\ndone :)\n";


	############################################################################################################

	function png_2_swf($src, $dst, $w, $h){

		$png_filename = $src;
		$dbl_filename = preg_replace('/png$/', 'dbl', $src);
		$swf_filename = $dst;

		#
		# convert png to dbl
		#

		exec("/usr/bin/png2dbl $png_filename");


		#
		# convert dbl to swf
		#

		ming_useswfversion(6);

		$movie = new SWFMovie();
		$movie->setRate(20.000000);
		$movie->setDimension($w, $h);
		$movie->setBackground(0xff,0x00,0x00);

		$fp = fopen($dbl_filename, "rb");
		$data = fread($fp,999999);
		fclose($fp);

		$the_img = new SWFBitmap($data);

		$s = new SWFShape();
		$f = $s->addFill($the_img);
		$s->setLeftFill($f);

		$s->drawLine($w, 0);
		$s->drawLine(0, $h);
		$s->drawLine(-$w, 0);
		$s->drawLine(0, -$h);

		$movie->add($s);
		$movie->save($swf_filename);

		#
		# delete dbl
		#

		unlink($dbl_filename);

		echo '.'; flush();
	}

	############################################################################################################

	function png_2_swf_tiled($src, $dst, $w, $h){

		$png_filename = $src;
		$dbl_filename = preg_replace('/png$/', 'dbl', $src);
		$swf_filename = $dst;

		#
		# convert png to dbl
		#

		exec("/usr/bin/png2dbl $png_filename");


		#
		# convert dbl to swf
		#

		ming_useswfversion(6);

		$movie = new SWFMovie();
		$movie->setRate(20.000000);
		$movie->setDimension($w, $h);
		$movie->setBackground(0xff,0x00,0x00);

		$fp = fopen($dbl_filename, "rb");
		$data = fread($fp,999999);
		fclose($fp);

		$the_img = new SWFBitmap($data);

		$s = new SWFShape();
		$f = $s->addFill($the_img, SWFFILL_TILED_BITMAP);
		$s->setLeftFill($f);

		$s->drawLine($w, 0);
		$s->drawLine(0, $h);
		$s->drawLine(-$w, 0);
		$s->drawLine(0, -$h);

		$movie->add($s);
		$movie->save($swf_filename);

		#
		# delete dbl
		#

		unlink($dbl_filename);

		echo '.'; flush();
	}

	############################################################################################################

	function solid_png($dst, $w, $h, $color){

		$swf_filename = $dst;

		if (preg_match('/#(..)(..)(..)/', $color, $matches)){
			$r = hexdec($matches[1]);
			$g = hexdec($matches[2]);
			$b = hexdec($matches[3]);
		}else{
			echo 'x';
		}

		ming_useswfversion(6);

		$movie = new SWFMovie();
		$movie->setRate(20.000000);
		$movie->setDimension($w, $h);
		$movie->setBackground(0xff,0x00,0x00);

		$squareshape = new SWFShape();
		$squareshape->setRightFill($r, $g, $b);
		$squareshape->drawLine($w ,0);
		$squareshape->drawLine(0, $h);
		$squareshape->drawLine(-$w, 0);
		$squareshape->drawLine(0, -$h);

		$squaresymbol = $movie->add($squareshape);
		$squaresymbol->moveTo(0, 0);

		$movie->save($swf_filename);

		echo '.'; flush();
	}

	############################################################################################################

?>