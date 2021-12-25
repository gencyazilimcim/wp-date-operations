class v_day_counter {
	
	public function __construct(){
		add_shortcode( 'v_day', array($this, 'salcodes_cta') );
	}
	public function v_date_grab($str){
	  // regex pattern will match any date formatted dd-mm-yyy or d-mm-yyyy with
	  // separators: periods, slahes, dashes
	  $c_pattern = '{.*?(\d\d?)[\\/\.\-]([\d]{2})[\\/\.\-]([\d]{4}).*}';
	  $date = preg_replace($c_pattern, '$3-$2-$1', $str);
	  return new \DateTime($date);
	}
	
	public function salcodes_cta($atts){
		ob_start();
		//countown
		$a = shortcode_atts( array(
		 'param' => 1
		), $atts );

		$time = current_time( 'mysql' );
		
		$param = trim($a['param']);		
		
		$no_whitespaces = trim($param); //no spaces

		$no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var($no_whitespaces, FILTER_SANITIZE_STRING ) );  // only numeric and string
		$no_whitespaces = preg_replace('/\s+/', '', $no_whitespaces); // space removed 

		$no_whitespaces = strtolower($no_whitespaces); //string to lower

		$param_array = preg_split("/[,|=]/", $no_whitespaces); // split => "," "|" "="
				
		$patterns = array(
            'Y'           =>'/^[0-9]{4}$/',
            'Y-m'         =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])$/',
            //'m-Y'         =>'/^([1-9]|0[1-9]|1[0-2])(-|\/)[0-9]{4}$/',
            'Y-m-d'       =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/',
            'Y-m-d H'     =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4])$/',
            'Y-m-d H:i'   =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4]):?(0|[0-5][0-9]|60)$/',
            'Y-m-d H:i:s' =>'/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\s(0|[0-1][0-9]|2[0-4]):?((0|[0-5][0-9]):?(0|[0-5][0-9])|6000|60:00)$/',
        );

		
		if(count($param_array) <= 2){
			if($param_array[0] == "this_current"){
				sanitize_text_field($param_array[1]);
				preg_replace('/[^a-z]/', '', $param_array[1]);
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

			}elseif($param_array[0] == "later_day" && is_numeric($param_array[1])){
				echo $param_array[1]." gün sonra";
			}elseif($param_array[0] == "countdown") {

				if( preg_match($patterns['Y'], $param_array[1]) ){
					echo "yıl tarih formatı doğru<br>";
					echo date("m-d-Y", strtotime("31-12-".$param_array[1]));
				}elseif(preg_match($patterns['Y-m'], $param_array[1])){
					echo "yıl ve ay tarih formatı doğru";
				}elseif(preg_match($patterns['Y-m-d'], $param_array[1])){
					echo "yıl ve ay ve gün tarih formatı doğru";
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
