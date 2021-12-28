class v_day_counter {
	public $str;
	
	public function __construct(){
		add_shortcode( 'v_day', array($this, 'salcodes_cta') );
	}
	public function v_date_grab($str){
	  // regex pattern will match any date formatted dd-mm-yyy or d-mm-yyyy with
	  // separators: periods, slahes, dashes
	  $c_pattern = '{.*?(\d\d?)[\\/\.\-]([\d]{2})[\\/\.\-]([\d]{4}).*}';
	  $date = preg_replace($c_pattern, '$3-$2-$1', $str);
	  return new DateTime($date);
	}
	
	public function salcodes_cta($atts){
		ob_start();
		//countown
		$a = shortcode_atts( array(
		 'param' => 1,
		 'message' => "mesaj eklemediniz"
		), $atts );

		$time = current_time( 'mysql' );		
		
		$no_whitespaces = trim($a['param']); //no spaces

		$no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var($no_whitespaces, FILTER_SANITIZE_STRING ) );  // only numeric and string
		$no_whitespaces = preg_replace('/\s+/', '', $no_whitespaces); // space removed 

		$no_whitespaces = strtolower($no_whitespaces); //string to lower

		$param_array = preg_split("/[,|=]/", $no_whitespaces); // param seperator => "," "|" "="
		
		/*
		 * valid date format
		 * 2021
		 * 2021-12
		 * 2021-12-30
		 * 2021-12-30 23
		 * 2021-12-30 23:30

		 * 12-2021
		 * 30-12-2021
		 * 30-12-2021 23
		 * 30-12-2021 23:30

		 * 12-30-2021
		 * 12-30-2021 23
		 * 12-30-2021 23:30
		 * */
		$patterns = array(
		    'Y'           =>'/^[0-9]{4}$/',
		    'Y-m'         =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])$/',
		    'Y-m-d'       =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/',
				'Y-m-d H'     =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4])$/',
		    'Y-m-d H:i'   =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4]):?(0|[0-5][0-9]|60)$/',

		    'm-Y'         =>'/^([1-9]|0[1-9]|1[0-2])(-|\/)[0-9]{4}$/',
		    'd-m-Y'       =>'/^([1-9]|0[1-9]|[1-2][0-9]|3[0-1])(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)[0-9]{4}$/',
		    'd-m-Y H'     =>'/^([1-9]|0[1-9]|[1-2][0-9]|3[0-1])(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)[0-9]{4}\s(0|[0-1][0-9]|2[0-4])$/',
		    'd-m-Y H:i'   =>'/^([1-9]|0[1-9]|[1-2][0-9]|3[0-1])(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)[0-9]{4}\s(0|[0-1][0-9]|2[0-4]):?(0|[0-5][0-9]|60)$/',

				'm-d-Y'       =>'/^([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])(-|\/)[0-9]{4}$/',
		    'm-d-Y H'     =>'/^([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])(-|\/)[0-9]{4}\s(0|[0-1][0-9]|2[0-4])$/',
		    'm-d-Y H:i'   =>'/^([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])(-|\/)[0-9]{4}\s(0|[0-1][0-9]|2[0-4]):?(0|[0-5][0-9]|60)$/',
		);

		
		if(count($param_array) <= 2){
			if($param_array[0] == "this_current"){
				sanitize_text_field($param_array[1]);
				$param_array[1] = preg_replace('/[^a-z.!?]/', '', $param_array[1]);
				switch ($param_array[1]) {
					case 'all':
						echo "Güncel gün => ".date("d.m.Y");
						break;
					case 'year':
						echo "Güncel yıl => ".date("Y");
						break;
					case 'month':
						echo "Güncel ay => ".date("F");
						break;
					case 'day':
						echo "Güncel gün => ".date("D");
						break;

					default:
						echo "d:Güncel gün => ".date("d.m.Y");
						echo $param_array[1];
						break;
				}

			}elseif($param_array[0] == "later_day"){
				if(is_numeric($param_array[1])){
					$param_array[1] =  preg_replace("/\D+/", "", $param_array[1]);
					echo $param_array[1]." gün sonra";	
				}else{
					echo "later_dat hata yaptınız.";
				}
			}elseif($param_array[0] == "countdown") {

				if( preg_match($patterns['Y'], $param_array[1]) ){
					echo "yıl tarih formatı doğru<br>";
					echo date("m-d-Y", strtotime("31-12-".$param_array[1]));
				}elseif(preg_match($patterns['Y-m'], $param_array[1])){
					echo "yıl ve ay tarih formatı doğru";
				}elseif(preg_match($patterns['m-Y'], $param_array[1])){
					echo "ters:ay ve yıl tarih formatı doğru";
				}elseif(preg_match($patterns['Y-m-d'], $param_array[1])){
					echo "yıl ve ay ve gün tarih formatı doğru";
				}elseif(preg_match($patterns['d-m-Y'], $param_array[1])){
					echo "ters: gün ve ay ve yıl tarih formatı doğru";
				}elseif(preg_match($patterns['Y-m-d H'], $param_array[1])){
					echo "yıl ve ay ve gün ve saat tarih formatı doğru";
				}elseif(preg_match($patterns['Y-m-d H:i'], $param_array[1])){
					echo "yıl ve ay ve gün ve saat ve dakika tarih formatı doğru";
				}else{
					echo "tarih formatı yanlış";
				}

				echo "<br>countdown sayacı => ". $param_array[1];
			}elseif($param_array[0] == "s_countdown") {

				/*if( preg_match($patterns['Y'], $param_array[1]) ){
					echo "[simple] => yıl tarih formatı doğru -> [Y]";
				}elseif(preg_match($patterns['Y-m'], $param_array[1])){
					echo "[simple] => yıl ve ay tarih formatı doğru -> [Y-m]";
				}elseif(preg_match($patterns['m-Y'], $param_array[1])){
					echo "[simple] =>  ay ve yıl tarih formatı doğru -> [m-Y]";
				}elseif(preg_match($patterns['Y-m-d'], $param_array[1])){
					echo "[simple] => yıl ve ay ve gün tarih formatı doğru -> [Y-m-d]";
				}elseif(preg_match($patterns['Y-m-d H'], $param_array[1])){
					echo "[simple] => yıl ve ay ve gün ve saat tarih formatı doğru -> [Y-m-d H]";
				}elseif(preg_match($patterns['Y-m-d H:i'], $param_array[1])){
					echo "[simple] => yıl ve ay ve gün ve saat ve dakika tarih formatı doğru -> [Y-m-d H:i]";
				}else{
					echo "[simple] => tarih formatı yanlış";
				}*/
				$n_date = self::v_date_grab( $param_array[1] );
				echo $param_date = $n_date->format('Y-m-d');

				foreach($patterns as $v_format => $fval){

					if( preg_match($patterns[$v_format], $param_array[1]) ){
						echo "[simple] => $v_format tarih formatı doğru ->";
						break;
					}
				}

				echo "<br>[simple] => countdown sayacı => ". $param_array[1];
			}elseif($param_array[0] == "count_message") {
				$a['message'] = esc_js($a['message']);
				if(is_numeric($param_array[1]) &&  isset($a['message'])){
					$param_array[1] =  preg_replace("/\D+/", "", $param_array[1]);
			?>
			<script>
			let timeleft = <?=$param_array[1]?>;
			let downloadTimer = setInterval(function(){
			  if(timeleft <= 0){
				clearInterval(downloadTimer);
				document.getElementById("countdown").innerHTML = "<?=$a['message']?>";
			  } else {
				document.getElementById("countdown").innerHTML = timeleft + " saniye sonra";
			  }
			  timeleft -= 1;
			}, 1000);
			</script>
			<?php
					echo '<div id="countdown"></div>';	
					
				}else{
					echo "count_message hata yaptınız";
				}
			}else{
				echo "genel hata<br>";
				echo $param_array[0]."<br>";
				echo $param_array[1];
			}

		}else{
			echo "<br>array count hata";
		}
		
		return $output;
		ob_get_clean();
	}
}


new v_day_counter();
