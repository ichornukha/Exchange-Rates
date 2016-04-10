<?php
$ch = curl_init('http://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=USD&date='.getDateForAPI().'&json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
$r = json_decode($result);
$r = $r[0];
echo $r->txt."\t".round($r->rate, 2)." грн.\n";
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

?>