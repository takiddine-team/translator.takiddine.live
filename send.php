<?php 

$start_time = microtime(true);

require_once 'test2.php';

require_once 'helpers.php';


class Translator {

    public $url = 'https://translator.takiddine.live/result/';
    
    public $file;
    public $content;
    public $filtred_content;
    public $translated_content;
    public $result;
    public $start_time;
    public $generated_file_name;
    public $translate_to;

    public $translated;
    public $translated_keys;
    public $remain;

    public function read_file_content($file){
        
        $jsonContent = file_get_contents($file);

        $this->content = json_decode($jsonContent, true);

        return $this;
    }

    public function filter_metas(){

        $filteredData = array();
        $metasValue = $this->content[0]['metas'];
        foreach ($metasValue as $key => $value) {
            if (strpos($key, '_') !== 0) {
                if( ! isset($value['url'])   && (!is_numeric($value) || strval(intval($value)) !== $value) && !preg_match('/^OPTION \d+$/', $value)) {
                    // $filteredData[$key] = strip_tags($value) ;
                    $filteredData[$key] = $value ;
                }
            }
        }
        $this->filtred_content = $filteredData;

        return $this;
    }


  

    public function translate_to_arabic($content) {
  	   return "Act as a Native Egyptian Translator and Content creator. Your task is to translate the provided json content with the highest-quality ever Arabic Language 100% human, professional, clear, and understandable. Make sure to use the proper Arabic sentence structure and wording order. Furthermore, the brand name \"Citizenship Invest\" should be translated into \"سيتيزنشيب انفيست\".  Don't leave any pieces of content out, and don't add any additional content other than the ones provided; stick to the provided content only and please keep the html tags in the text DO NOT REMOVE THEM" ;
    }
 
    public function translate_to_chinese ($content) {
  	   return "Act as a Native Chinese Translator and Content creator. Your task is to translate the provided JSON content with the highest-quality ever Simplified Chinese Language 100% human, professional, clear, and understandable. Make sure to use the proper Simplified Chinese sentence structure and order. Furthermore, the brand name \"Citizenship Invest\" should not be translated.  Don't leave any pieces of content out, and don't add any additional content other than the ones provided; stick to the provided content only and please keep the html tags in the text DO NOT REMOVE THEM " ;
    }

    public function translate_to_spanish($content) {
        return "Act as a Native Spanish Translator and Content creator. Your task is to translate the provided JSON content with the highest-quality ever Spanish Language 100% human, professional, clear, and understandable. Make sure to use the proper sentence structure and order. Furthermore, the brand name \"Citizenship Invest\" should not be translated.  Don't leave any pieces of content out, and don't add any additional content other than the ones provided; stick to the provided content only and please keep the html tags in the text DO NOT REMOVE THEM and please keep the html tags in the text DO NOT REMOVE THEM" ;
    }

    public function translate_to_frensh($content) {
        return "Act as a Native French Translator and Content creator. Your task is to translate the provided JSON content with the highest-quality ever French Language 100% human, professional, clear, and understandable. Make sure to use the proper sentence structure and order. Furthermore, the brand name \"Citizenship Invest\" should not be translated.  Don't leave any pieces of content out, and don't add any additional content other than the ones provided; stick to the provided content only and please keep the html tags in the text DO NOT REMOVE THEM and please keep the html tags in the text DO NOT REMOVE THEM" ;
    }
    
    public function translate_to_iranian($content) {
        return "Act as a Native Iranian Translator and Content creator. Your task is to translate the provided JSON content with the highest-quality ever Persian Language 100% human, professional, clear, and understandable. Make sure to use the proper Persian sentence structure and order. Furthermore, the brand name \"Citizenship Invest\" should be translated into \"سیتیزنشیپ \".  Don't leave any pieces of content out, and don't add any additional content other than the ones provided; stick to the provided content only and please keep the html tags in the text DO NOT REMOVE THEM" ;
    }
  
    public function translate_to_kurdish($content) {
        return "Act as a Native Kurdish Sorani Translator and Content creator. Your task is to translate the provided JSON content with the highest-quality ever Kurdish Sorani Language 100% human, professional, clear, and understandable. Make sure to use the proper Kurdish Sorani sentence structure and order. Furthermore, the brand name \"Citizenship Invest\" should be translated into \"ستیزنشپ ئینڤێست\".  Don't leave any pieces of content out, and don't add any additional content other than the ones provided; stick to the provided content only and please keep the html tags in the text DO NOT REMOVE THEM" ;
    }
  
    public function translate_to_russian($content) {
        return "Act as a Native Russian Translator and Content creator. Your task is to translate the provided JSON content with the highest-quality ever Russian Language 100% human, professional, clear, and understandable. Make sure to use the proper sentence structure and order. Furthermore, the brand name \"Citizenship Invest\" should not be translated.  Don't leave any pieces of content out, and don't add any additional content other than the ones provided; stick to the provided content only and please keep the html tags in the text DO NOT REMOVE THEM" ;
    }



    public function translate_with_chatGPT($content){
        
        $content = json_encode($content,JSON_UNESCAPED_UNICODE   | JSON_HEX_TAG );
      
        if( $this->translate_to == 'Arabic' ) {
           $prompt = $this->translate_to_arabic($content);
        }
        
        if( $this->translate_to == 'Chinese' ) {
            $prompt = $this->translate_to_chinese($content);
        }    
                
        if( $this->translate_to == 'Spanish' ) {
            $prompt = $this->translate_to_spanish($content);
        }

        if( $this->translate_to == 'French' ) {
            $prompt = $this->translate_to_frensh($content);
        }

        if( $this->translate_to == 'Iranian' ) {
            $prompt = $this->translate_to_iranian($content);
        } 
      
        if( $this->translate_to == 'Kurdish Sorani' ) {
            $prompt = $this->translate_to_kurdish();
        }
       
        if( $this->translate_to == 'Russian' ) {
            $prompt = $this->translate_to_russian($content);
        }     

        $response = translate($prompt . $content);
        
        $this->translated[] =  json_decode(  $response['choices'][0]['message']['content'] , true );

        if( empty($response)) {
            $this->remain[] = $content;
        }

        return json_decode( $response['choices'][0]['message']['content'] , true );

    }


    public function split_into_chunks($inputArray,$number_of_splits){
         
        $chunkSize = $number_of_splits;
        $splitArrays = [];

        $chunk = [];
        
        foreach ($inputArray as $key => $value) {
            $chunk[$key] = $value;
            if (count($chunk) === $chunkSize) {
                $splitArrays[] = $chunk;
                // return $splitArrays;
                $chunk = [];
            }
        }

        // Add the remaining chunk if it's not empty
        if (!empty($chunk)) {
            $splitArrays[] = $chunk;
        }

        // dd($splitArrays);
        return $splitArrays;
    }

    public function split_and_tanslate ( $content ) {

        // i'm splitting the content so i can make it easier for chatgpt , to not stop 
        $splitArrays = $this->split_into_chunks($content,10);


      //  dd($splitArrays);
        // translate the file pace by pace
        foreach ($splitArrays as $index => $splitArray) {      
            // dd($splitArray) ;
            $splitArrays[$index] = $this->translate_with_chatGPT($splitArray);
        }

        // dd($splitArrays);
        // merge all translation in on array again 
        return flattenArray($splitArrays);
    }


    public function update_content($array){

        foreach ($array as $key => $value) {

            if (is_array($value)) {
                $this->update_content($value);
            }else {
                $this->content[0]['metas'][$key] = $value;
            }

        }
    }

    public function update_the_content_with_translation(){

        $metasValue = $this->content[0]['metas'];

        $translated = $this->translated;

        foreach ($translated as $key => $value) {

            $this->update_content($value);

        }
        // sv($translated);
        // sv($this->content);
 
        return $this;
    }


    public function generate_the_file(){
        $this->result = json_encode($this->content, JSON_UNESCAPED_UNICODE );
        $fileName = "result_" . date("Y-m-d_H-i-s") . ".json";
        file_put_contents(__DIR__.'/result/'.$fileName, $this->result,true);
        $this->generated_file_name = $fileName;
    }
    
    public function get_the_download_url(){
        $html = '<a target="_blank" href="'. $this->url . $this->generated_file_name  .'"> <b>download file</b> </a>';
        return $html;
    }


    public function translate(){

        $this->translated_content = $this->split_and_tanslate($this->filtred_content);
        // dd( $this->translated  );
        return $this;
    }


    public function generate() {

        // update the content with translation
        $this->update_the_content_with_translation();

        // generate the file 
        $this->generate_the_file();

        // generate and print the url 
        echo $this->get_the_download_url();

        // Record the end time and calculate the time taken in seconds
        $endTime = microtime(true);
        $timeTaken = $endTime - $this->start_time;

        sv($this->remain);
        echo "<br> the translation took " . convertSecondsToReadable((int)$timeTaken) . " to complete.";
        exit;
    }



}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['the_content']) && $_FILES['the_content']['error'] === UPLOAD_ERR_OK) {
        
        $uploadedFile = $_FILES['the_content']['tmp_name'];

        $selectedLanguage = $_POST['the_lang'];
        
        $jsonContent = file_get_contents($uploadedFile);

        $translator = new Translator();

        $translator->start_time = $start_time;

        $translator->content = json_decode($jsonContent, true);

        $translator->translate_to = $selectedLanguage;

        $translator->filter_metas();

        $translator->translate();

        $translator->generate();

    } 
}