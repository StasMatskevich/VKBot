<?php

    class VK {
        private $access_token;
        private $confirm_key;
        private $status;

        function __construct($status, $access_token, $confirm_key) {
            $this->status = $status;
            $this->access_token = $access_token;
            $this->confirm_key = $confirm_key;
        }

        public function getConfirm() {
            return $this->confirm_key;
        }

        /*
            The first argument is the api method
            The second argument is the body of the query(array type)
         */
        public function vkAPI($method, $params) {
            $url = 'https://api.vk.com/method/'; // url vk api
            $url .= $method.'?'.http_build_query($params);

            // sending a request and receiving a response
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($json, true);

            return $res;
        } // sending request to vk api

        public function message($vk_id, $message, $attachments = array()) {
            $method = 'messages.send'; // api method
            $params = [
                'peer_id' => $vk_id,
                'message' => $message,
                'attachment' => implode(',', $attachments),
                'v' => API_VERSION,
                'access_token' => $this->access_token
            ]; // query parameters

            /*if($this->status == 'dev') {
                echo $message.'\n'; // request to test for development
                return true;
            }*/

            return $this->vkAPI($method, $params)['response']; // request to api
        } // sending a message

        public function getWallRandomPost($community)
        {
            $method = 'wall.get'; 
            $params = [
                'owner_id' => $community,
                'count' => '100',
                //'offset' => range(0, 100),
                'v' => API_VERSION,
                //'access_token' => $this->access_token
            ]; 
            $res = $this->vkAPI($method, $params);
            print_r($res);
            $res = $res['response'];
            return $res['items'][range(0, $res['count'] - 1)];
        }

        public function getUser($user_id) {
            $user = $this->vkAPI('users.get', [
                'user_ids' => $user_id,
                'v' => API_VERSION,
                'access_token' => $this->access_token
            ]); // request to api

            if(isset($user['error'])) {
                //echo "getUser Error! ";
                //print_r($user['error']);
                return 'Ошибка!';
            }

            if(count($user['response']) == 0) {
                return 'Хмм... Что-то не так...';
            }
            return $user['response'][0];
        } // get user info in VK
    }