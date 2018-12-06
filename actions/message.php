<?php
    //$user_id = 0;
    if(!isset($data['object']['peer_id'])) $user_id = $data['object']['from_id']; // user id in vk
    else $user_id = $data['object']['peer_id']; // user id in vk
    $bot_phrases = include('src/phrases/bot.php'); // connecting bot phrases
    $user_phrases = include('src/phrases/user.php'); // connecting user phrases
    $communities = include('src/phrases/communities.php');

    $message = $data['object']['text']; // message
    include('src/commands.php');

    if(strlen($message) > 0 && $message[0] == '[') 
    {
        $message = substr($message, strrpos($message, ']') + 1);
        if(strlen($message) > 0 && ($message[0] == ' ' || $message[0] == ','  || $message[0] == '.')) $message = substr($message, 1);
        //echo $message;
    }

    if(strlen($message) > 0 && $message[0] == '/') { // if message is command
        $res = execCommand($message);
        if($res != false) // if is there such a team
            $vk->message($user_id, $res); // process the command and send the response
        else
            $vk->message($user_id, $bot->getPhrases($bot_phrases['undefined'])); // send the response - undefined phrases

        return; // quit the script
    }

    $upper_message = mb_strtoupper($message, "UTF-8");
    $phrase_key = false;
    foreach ($user_phrases as $key=>$item) { // search for a phrase and save its key
        foreach ($item as $phrase) {
            if(mb_strtoupper($phrase, "UTF-8") == substr($upper_message, 0, strlen($phrase))) { // if the phrase is found
                $phrase_key = true; // set flag
                break;
            }
        }

        if($phrase_key) { // if the phrase is found
            $phrase_key = $key; // save phrase key
            break;
        }
    }

    $phrase_key = !$phrase_key ? 'undefined' : $phrase_key; // check the key to existence
    $phrase = $bot->getPhrases($bot_phrases[$phrase_key]); // get random phrases in array

    if($phrase[0] == '/') { // if phrases is command
        $vk->message($user_id, execCommand($phrase)); // process the command and send the response
    } else {
        $vk->message($user_id, $phrase); // send the response message
    }
    