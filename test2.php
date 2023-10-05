<?php


function translate($content){
        // Replace 'YOUR_API_KEY' with your actual API key
        $api_key = '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';

        // Prepare the API request
        $data = array(
            
            "model" => "gpt-3.5-turbo",
            "messages" => array(
                array(
                    "role" => "user",
                    "content" => $content
                )
            )
        );

        // Set up cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        ));




        // Execute the API request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            curl_close($ch);
            exit;
        }

        // Close cURL
        curl_close($ch);

        // Decode the API response
        // $translated_text = json_decode($response, true)['choices'][0]['text'];
        return json_decode($response, true);

}
