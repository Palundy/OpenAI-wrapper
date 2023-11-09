<?php
/**
 * Easily perform cURL requests.
 */
class cURL {

    /**
     * Send a `GET` request.
     * 
     * @param string $URL
     * The URL to fetch.
     * 
     * @param array $Headers [optional]
     * The array with headers.
     * 
     * 
     * @return array|false
     * An array with the response, or `false` if
     * an error occurs.
     */
    public static function GET($URL, $Headers = []) {
        
        $cHandle = curl_init();
        curl_setopt($cHandle, CURLOPT_URL, $URL);
        curl_setopt($cHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cHandle, CURLOPT_HTTPHEADER, $Headers);

        $returnData = curl_exec($cHandle);
        if (in_array("Content-Type: application/json", $Headers)) {
            $returnData = json_decode($returnData, true);
        }
        curl_close($cHandle);
        
        return $returnData;
    }


    /**
     * Send a `POST` request.
     * 
     * @param string $URL
     * The URL to fetch.
     * 
     * @param $Postfields [optional]
     * The data that gets posted.
     * Automatically converts to JSON
     * if the header `Content-Type: application/json`
     * has been set.
     * 
     * @param array $Headers [optional]
     * The array with headers.
     * 
     * 
     * @return array|false
     * An array with the response, or `false` if
     * an error occurs.
     */
    public static function POST($URL, $Postfields = [], $Headers = []) {
        
        $cHandle = curl_init();
        $Headers[] = OpenAI::AuthorizationHeader();

        curl_setopt($cHandle, CURLOPT_URL, $URL);
        curl_setopt($cHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cHandle, CURLOPT_HTTPHEADER, $Headers);
        curl_setopt($cHandle, CURLOPT_POST, true);
        curl_setopt($cHandle, CURLOPT_POSTFIELDS, (in_array("Content-Type: application/json", $Headers) ? json_encode($Postfields) : http_build_query($Postfields)));
    
        $returnData = curl_exec($cHandle);
        if (in_array("Content-Type: application/json", $Headers)) {
            $returnData = json_decode($returnData, true);
        }
        curl_close($cHandle);

        return $returnData;
    }
}