<?php

function translate($content) {


$headers = array(
    "Accept: */*",
    "Content-Type: application/json",
    "Origin: https://chatgpt.mal.io",
    "Referer: https://chatgpt.mal.io/",
    "Selected-Language: ar_SA",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36",
    "sec-ch-ua: \"Not/A)Brand\";v=\"99\", \"Google Chrome\";v=\"115\", \"Chromium\";v=\"115\"",
    "sec-ch-ua-mobile: ?0",
    "sec-ch-ua-platform: \"Windows\""
);
$url = "https://api.mal.finance/chatgpt/chat";




$data = array(
    "id" => "ed51f21b-94d3-4331-a859-1a520316f4c6",
    "model" => "gpt-3.5-turbo",
    "messages" => array(
        array(
            "role" => "user",
            "content" => $content
        )
    )
);

$postData = json_encode($data);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    
   return json_decode($response, true);
}

curl_close($ch);


}
?>
