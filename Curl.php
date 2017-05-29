<?php

class Curl
{

    private $url = '';
    private $format = '&json';
    private $date = '';
    private $valcode = '';

    /**
     * Curl constructor.
     * ['url'=>,'date'=> , 'valcode'=>,]
     * @var array $params .
     * @throws InvalidArgumentException;
     */
    public function __construct($params = [])
    {
        if (null !== $params && count($params) !== 0) {
            $this->date .= $params['date'];
            $this->valcode .= $params['valcode'];
            $this->url = $params['url'];
        } else {
            throw new InvalidArgumentException('Params must be set.', 400);
        }
    }

    public function get()
    {
        $requestString = $this->url . http_build_query(['valcode' => $this->valcode,
                    'date' => $this->date,]
            ) . $this->format;
        $chan = curl_init($requestString);
        curl_setopt($chan, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($chan);
        curl_close($chan);
        return $result;

    }
}