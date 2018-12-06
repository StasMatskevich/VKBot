<?php

    class Weather {
        public $access_key;

        function __construct($access_key) {
            $this->access_key = $access_key;
        }

        public function getWeather($city) {

    		 $params = [
                'appid' => $this->access_key,
                'q' => $city,
                //'type' => 'accurate',
                'lang' => 'ru',
                'units' => 'metric'
            ];

            $url = 'https://api.openweathermap.org/data/2.5/'; // url vk api
            $url .= 'weather?'.http_build_query($params);

            // sending a request and receiving a response
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($json, true);

            if(isset($res['cod']) && $res['cod'] != '200') {
                if($res['cod'] == '404') return 'Запрос не дал результатов';
                else return 'Ошибка '.$res['cod'];
            }

            return $res;
        }
    }