<?php


$printingText = validate();
$ch = curl_init('http://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=USD&date='.getDateForAPI().'&json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
$r = json_decode($result, true);
$r = $r[0];
echo $r[$printingText].": ".round($r['rate'],2)." грн.\n";


function getDateForAPI()
{
	$weekDay = date('N');
	switch ($weekDay) {
		case 7:
			return date('Ymd', strtotime('-2day'));
			break;
		case 6:
			return date('Ymd', strtotime('-1day'));
			break;
		default:
			return date('Ymd');
			break;
	}

}


function validate(){
	$arguments = $_SERVER['argv'];
	$flag = $arguments[1];
	//var_dump($flag);
	//die();
	switch ($flag){
		case('--code'):
			return 'r030';
		case('--full'):
			return 'txt';
		default:
			return 'cc';
	}
	
	
}
?>
