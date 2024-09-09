<?php

if (!function_exists('dateindo')) {
	function dateindo($tanggal)
	{	
		$date = date("d-m-Y", strtotime($tanggal));
		
		$bulan = array (
			1 =>   'Jan',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Apr',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Jul',
			8 => 'Ags',
			9 => 'Sep',
			10 => 'Okt',
			11 => 'Nov',
			12 => 'Des'
		);
		
		$pecahkan = explode('-', $date);
		
		return $pecahkan[0] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
	}
}

if (!function_exists('getParamsUrl')) {
	function getParamsUrl($params)
	{	
		$par = null;
		$i = 0;
		foreach($params as $key => $value){
			if($i==0){
				$par .= "?".$key."=".$value;
			}else{
				$par .= "&".$key."=".$value;
			}
			$i++;
		}
		return $par;
	}
}

if (!function_exists('daydate')) {
	function daydate($tanggal)
	{	
		$date = strtolower(date_format(date_create($tanggal), "D"));
		//dd($date);
		$hari = "";
		if($date=="sun"){
			$hari = "Minggu";
		}elseif($date=="mon"){
			$hari = "Senin";
		}elseif($date=="tue"){
			$hari = "Selasa";
		}elseif($date=="wed"){
			$hari = "Rabu";
		}elseif($date=="thu"){
			$hari = "Kamis";
		}elseif($date=="fri"){
			$hari = "Jum'at";
		}elseif($date=="sat"){
			$hari = "Sabtu";
		}else{
			$hari = "tidak valid";
		}
		
		return $hari;
	}
}
if (!function_exists('terbilang')) {
	
	function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " Belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " Seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " Seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
	
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}
	
}

if (!function_exists('tonumber')) {
	function tonumber($data){
		
		$data = number_format($data, 0, ',', '.');
		return $data;
		
	}
}

if (!function_exists('tonumberround')) {
	function tonumberround($data){
		
		$data = number_format($data, 2, ',', '.');
		return $data;
		
	}
}


if (!function_exists('tanggalMerah')) {
	//fungsi check tanggal merah
	function tanggalMerah($value) {
		$tgl = date("Ymd", strtotime($value));
		$tgl1 = date("Y-m-d", strtotime($value));
		//default time zone
		date_default_timezone_set("Asia/Jakarta");
		$array = json_decode(file_get_contents("https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json"),true);
		$d_tgl = false;
		if(isset($array[$tgl])){
			$d_tgl = true;
		}elseif(date("D",strtotime($tgl1))==="Sun"){
			$d_tgl = true;
		}else{
			$d_tgl = false;
		}
		
		return $d_tgl;
	}
}

if (!function_exists('toMinutes')) {
	function toMinutes($str) {
		$jam = (Double)date("H", strtotime($str))*60;
		$minute = (Double)date("i", strtotime($str));
		$second = (Double)date("s", strtotime($str))/60;
		
		$total = $jam+$minute+$second;
		
		return $total;
	}
}

if (!function_exists('toHours')) {
	function toHours($minutes) {
		$hours = ($minutes -   floor($minutes / 60) * 60);
		
		return $hours;
	}
}

if (!function_exists('detect_chat_id')) {
    /**
     * Normalize chat id.
     *
     * @param $chatId
     * @return string
     */
    function detect_chat_id($chatId)
    {
        $chatId = str_replace([' ', '+'], '', $chatId ?? '');
        /*if (strpos($chatId, '-') !== false) {
            if (!(strpos($chatId, '@g.us') !== false)) {
                $chatId .= '@g.us';
            }
        } else if (!(strpos($chatId, '@c.us') !== false)) {
            $chatId = preg_replace('/^08/', '628', $chatId);
            $chatId .= '@c.us';
        }*/

        if (!(strpos($chatId, '@c.us') !== false) && (preg_match('/^08/', $chatId) || preg_match('/^628/', $chatId)) && strlen($chatId) <= 15) {
            $chatId = preg_replace('/^08/', '628', $chatId);
            // $chatId .= '@c.us';
        } else if (!(strpos($chatId, '@g.us') !== false) && !(strpos($chatId, '@c.us') !== false)) {
            // $chatId .= '@g.us';
        }

        return $chatId;
    }
}