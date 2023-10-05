<?php 

$start_time = microtime(true);

require_once 'test.php';

require_once 'helpers.php';


/*

how to make sure all translated is there 
save translated in a session 
after all is done , clean the session 

*/

class Translator {

    public $url = 'http://cleaner.test/result/';
    
    public $file;
    public $content;
    public $filtred_content;
    public $translated_content;
    public $result;
    public $start_time;
    public $generated_file_name;

    public function read_file_content($file){
        
        $jsonContent = file_get_contents($file);

        $this->content = json_decode($jsonContent, true);

        return $this;
    }

    public function filter_metas(){

        // this function to get only the values that needs to be translated

        $filteredData = array();
        $metasValue = $this->content[0]['metas'];
        foreach ($metasValue as $key => $value) {
            if (strpos($key, '_') !== 0) {
                if( ! isset(  $value['url'] ) ) {
                    $filteredData[] = $value ;
                }
            }
        }
        $this->filtred_content = $filteredData;
        return $this;
    }

    
    public function countTokens($text) {
        $words = str_word_count($text);
        $punctuation = preg_match_all('/[.,;!?]/', $text, $matches);
        return $words + $punctuation;
    }

    public function translate_with_chatGPT($content){
        $content = json_encode($content,true);
        $response = translate("translate this json from english to frensh :  " . $content);
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
        return $splitArrays;
    }

    public function split_and_tanslate ( $content ) {

        // i'm splitting the content so i can make it easier for chatgpt , to not stop 
        $splitArrays = $this->split_into_chunks($content,20);

        // dd($splitArrays);

        // translate the file pace by pace
        foreach ($splitArrays as $index => $splitArray) {      
            // dd($splitArray) ;
            $splitArrays[$index] = $this->translate_with_chatGPT($splitArray);
        }

        // merge all translation in on array again 
        return flattenArray($splitArrays);
    }


    public function update_the_content_with_translation(){

        $x = 0;

        $metasValue = $this->content[0]['metas'];

        foreach ($metasValue as $key => $value) {
            if (strpos($key, '_') !== 0) {
                if( ! isset(  $value['url'] ) ) {
                    if( isset($this->translated_content[$x])) {
                        $this->content[0]['metas'][$key] = $this->translated_content[$x];
                    }
                    $x++;
                }
            }
        }
        
        return $this;
    }

    public function generate_the_file(){
        $this->result = json_encode($this->content, true);
        $fileName = "result_" . date("Y-m-d_H-i-s") . ".json";
        file_put_contents(__DIR__.'/result/'.$fileName, $this->result,true);
        $this->generated_file_name = $fileName;
    }
    
    public function get_the_download_url(){
        return $this->url . $this->generated_file_name;
    }


    public function translate(){

        $this->translated_content = $this->split_and_tanslate($this->filtred_content);
        
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

        echo "<br> the translation took approximately " . convertSecondsToReadable((int)$timeTaken) . " to complete.";
        exit;
    }



}


$file = 'content.json';

$translator = new Translator();

$translator->start_time = $start_time;

$translator->read_file_content($file);

$translator->filter_metas();

$translator->translate();

$translator->generate();