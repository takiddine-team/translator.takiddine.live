<?php

$apiKey = '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'; // Replace with your OpenAI API key

// Input text to be translated
$inputText = "Hello, how are you?";

// Make API request
$response = translateText($inputText, $apiKey);

// Output translated text
$translatedText = 
echo "Translated Text: $translatedText";

// Function to make API request
function translateText($text, $apiKey) {
    $url = 'https://api.openai.com/v1/engines/davinci/completions';
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    );
    $data = array(
        'prompt' => ': "' . $text . '"',
        'max_tokens' => 50
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    echo '<pre>';
    print_r($response);
    exit;
    // return 
}