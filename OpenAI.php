<?php
class OpenAI {

    // API essential information
    private static $API_KEY = "YOUR_API_KEY";
    public const _API_URL = "https://api.openai.com/v1";

    // Endpoints
    public const _CHAT_COMPLETIONS_ENDPOINT = "/chat/completions";


    /**
     * Initialise a `ChatCompletions` object.
     * 
     * @return ChatCompletions
     */
    public static function ChatCompletions($ConversationId = false) {

        // Retrieve the API key if it isn't set
        self::retrieveApiKey();
        
        // Initialise a ChatCompletions object
        include_once("ChatCompletions.php");
        include_once("cURL.php");

        return new ChatCompletions($ConversationId);
    }


    /**
     * Set the API key.
     * 
     * @return void
     */
    private static function retrieveApiKey() {
        if (self::$API_KEY == "YOUR_API_KEY") {
            $ENV = parse_ini_file(".env");
            self::$API_KEY = $ENV["API_KEY"];
        }
    }


    /**
     * Return the Authorization header
     * 
     * @return string
     * The header.
     */
    public static function AuthorizationHeader() {
        return "Authorization: Bearer " . self::$API_KEY;
    }
}