<?php
    /*
     * This file contains the commands that send the user
     * The array key is the command itself
     * All commands must begin with a `/`
     *
     * Commands are used to process entered data or actions on user messages
     * You can create any number of commands
     *
     * The most striking example of a command is the output of the username of the person who sent the message
     *
     * Commands can be combined with the phrases of the bot. This will create a more live communication with the bot
     *
     * When the application grows, you can create commands to register users to the database
     *
     * All commands must return message text
     *
     * the command function must have two arguments
     *  - a copy of the class vk api
     *  - data from the server vk
     */
    function execCommand($command)
    {
        global $vk, $weather, $message, $data;
        if($command == '/hello') { // command to greet the user
            $user = $vk->getUser($data['object']['from_id']);
            if($user != 'Error') return "Привет, ".$user['first_name']." ".$user['last_name']."!";
            else return $user;
        }
        else if($command == '/my') { // output username
            $user = $vk->getUser($data['object']['from_id']);
            if($user != 'Error') return "Тебя зовут '".$user['first_name']." ".$user['last_name']."'";
            else return $user;
        }
        else if($command == '/weather') { // output username
            $city = substr($message, strrpos($message, ' ') + 1);
            $res = $weather->getWeather($city);
            //$user = $vk->getUser($data['object']['from_id']);
            //if($user != 'Error') return "Тебя зовут '".$user['first_name']." ".$user['last_name']."'";
            //else return $user;
            if(!isset($res['cod'])) return $res;
            //echo (substr($res, 0, 6) == 'Ошибка');
            //print_r($res);
            else return $res['main']['temp'].' С, '.$res['wind']['speed'].' м/с, '.$res['weather']['0']['description'];
        }
        else if($command == '/meme') { // output username
            $curl = curl_init('https://www.reddit.com/r/dankmemes/new.json?limit=10');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($json, true);
            return 'Вот '.$res['data']['children'][rand(0,9)]['data']['url'];
        }
        else return false;
    }