<?php
require('Curl.php');

/**
 * Class Rates
 */
class Rates
{

    private $date = '';
    private $url = 'http://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?';
    private $printingText = '';
    private $valcode = 'USD';
    private $response = [];
    private $preDate = '';
    private $yesterdaysRate = [];

    /**
     * Rates constructor.
     * @var string $date
     */
    public function __construct($date)
    {
        $date = strtotime($date);

        if (!$date) {
            $this->date = $this->getDateForAPI();
            $this->preDate = date('Ymd', strtotime('-1day', strtotime($this->date)));
        } else {
            $this->date = $date;
            $this->preDate = date('Ymd', strtotime('-1day', strtotime($this->date)));
        }
        $this->printingText = $this->validate();

    }


    private function getDateForAPI()
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


    private function validate()
    {
        $arguments = $_SERVER['argv'];
        $flag = @$arguments[1];
        switch ($flag) {
            case('--code'):
                return 'r030';
            case('--full'):
                return 'txt';
            default:
                return 'cc';
        }


    }

    public function loadData()
    {
        try {
            $result = $this->doApiRequest($this->date, $this->valcode);
        } catch (InvalidArgumentException $ex) {
            $result = [];
        }
        $response = json_decode($result, true);
        $response = reset($response);
        $this->response = $response;
        try {
            $result = $this->doApiRequest($this->preDate, $this->valcode);
        } catch (InvalidArgumentException $ex) {
            $result = [];
        }
        $yesterdaysRate = json_decode($result, true);
        $yesterdaysRate = reset($yesterdaysRate);
        $this->yesterdaysRate = $yesterdaysRate;

        return $this;
    }

    public function printResponseData()
    {
        if (null !== $this->response):
            echo $this->formatResponse();
        else:
            echo 'Oops, no rates' . PHP_EOL;
        endif;
    }

    private function doApiRequest($date, $valcode){
        $resp =  (new \Curl(['url' => $this->url, 'date' => $date, 'valcode' => $valcode,]))->get();
        return $resp;
    }
    private function formatResponse()
    {
        $output = $this->response[$this->printingText];
        $output .= ': ';
        $output .= round($this->response['rate'], 2);
        $output .= ' UAH';
        $output .= ($this->response['rate'] > $this->yesterdaysRate['rate']) ? '↑' : '↓';
        $output .= '(' . round($this->yesterdaysRate['rate'], 2) . ')';
        $output .= PHP_EOL;
        return $output;
    }

}