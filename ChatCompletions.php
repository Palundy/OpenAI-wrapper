<?php
class ChatCompletions {

    // URL variables
    private const ENDPOINT = "/chat/completions";
    private $URL = null;


    // Instance variables
    private $Model = "gpt-4";
    private $SystemMessage = "You are a helpful assistant.";
    private $MaxTokens = 256;
    private $MessageThread = [];
    private $TokenUsage = [
        "prompt_tokens" => 0,
        "completions_tokens" => 0,
        "total_tokens" => 0
    ];
    private $finishReason = null;
    private $storeObject = false;
    private $ChatCompletionId = null;
    private $hasImages = false;



    /**
     * Instantiate the `ChatCompletions` class.
     */
    public function __construct($ChatCompletionId = false) {
        $this->URL = OpenAI::_API_URL . OpenAI::_CHAT_COMPLETIONS_ENDPOINT;

        // Check whether to retrieve a stored conversation.
        if ($ChatCompletionId === false) {
            return;
        }
        
        // Retrieve the stored conversation
        $serializedObject = file_get_contents(
            __DIR__ . "/objects/" . $ChatCompletionId . ".txt"
        );
        
        // Deserialize the object
        $deserializedObject = unserialize($serializedObject);
        foreach($deserializedObject as $Key => $Value) {
            $this->$Key = $Value;
        }
    }
    
    /**
     * Deconstructs an instance of the `ChatCompletions` class.
     * 
     * @return void
     */
    public function __destruct() {

        // Check whether the conversation needs to be stored
        if ($this->storeObject === false) {
            return;
        }
        
        // Check whether a conversation has been created
        if (is_null($this->ChatCompletionId)) {
            return;
        }

        // Check whether the /objects directory exists
        if (file_exists(__DIR__ . "/objects") === false) {
            mkdir(__DIR__ . "/objects");
        }

        // Store the conversation
        $serializedObject = serialize($this);
        file_put_contents(
            __DIR__ . "/objects/" . $this->ChatCompletionId . ".txt",
            $serializedObject
        );
        return;
    }


    /**
     * Sets the model to be used.
     
     * @param string $Model
     * The model.
     * 
     * 
     * @return ChatCompletions
     */
    public function setModel($Model) {
        // Check whether images have been added
        //  in that case only the model `gpt-4-vision-preview`
        //  can be used.
        if ($this->hasImages) {
            return $this;
        }
        $this->Model = $Model;
        return $this;
    }


    /**
     * Sets whether to store the conversation
     * to be able to later retrieve the same conversation.
     * 
     * @param bool $Choice
     * 
     * 
     * @return ChatCompletions
     */
    public function storeConversation($Choice) {
        $this->storeObject = boolval($Choice);
        return $this;
    }


    /**
     * Sets the system message.
     *
     * @param string $SystemMessage
     * The system message.
     * 
     * 
     * @return ChatCompletions
     */
    public function setSystemMessage($SystemMessage) {
        $this->SystemMessage = $SystemMessage;
        return $this;
    }


    /**
     * Adds a user message.
     *
     * @param string $Message
     * The user message.
     * 
     * 
     * @return ChatCompletions
     */
    public function addUserMessage($Message) {
        $this->MessageThread[] = [
            "role" => "user",
            "content" => $Message
        ];
        return $this;
    }


    /**
     * Adds an assistant message.
     *
     * @param string $Message
     * The assistant message.
     * 
     * 
     * @return ChatCompletions
     */
    public function addAssistantMessage($Message) {
        $this->MessageThread[] = [
            "role" => "assistant",
            "content" => $Message
        ];
        return $this;
    }


    /**
     * Sets the maximum token amount.
     *
     * @param int $MaxTokens
     * The maximum amount of tokens.
     * 
     * 
     * @return ChatCompletions
     */
    public function setMaxTokens($MaxTokens) {
        $this->MaxTokens = $MaxTokens;
        return $this;
    }


    /**
     * Appends an image to the conversation.
     * 
     * @param string $ImageURL
     * An url to an image.
     * 
     * 
     * @return ChatCompletions
     */
    public function addImage($ImageURL) {

        // Change the model to:
        $this->setModel("gpt-4-vision-preview");
        $this->hasImages = true;

        $this->MessageThread[] = [
            "role" => "user",
            "content" => [[
                "type" => "image_url",
                "image_url" => ["url" => $ImageURL]
            ]]
        ];
        return $this;
    }


    /**
     * Show a crude format of the conversation.
     * 
     * @return ChatCompletions
     */
    public function showConversation() {
        print_r($this->MessageThread);
        return $this;
    }


    /**
     * Show the last response.
     * 
     * @return ChatCompletions
     */
    public function showResponse() {
        $lastMessage = $this->MessageThread[array_key_last($this->MessageThread)];
        echo $lastMessage["content"];
        return $this;
    }


    /**
     * Returns the last response.
     * 
     * @return string
     */
    public function getResponse() {
        $lastMessage = $this->MessageThread[array_key_last($this->MessageThread)];
        return $lastMessage["content"];
    }


    /**
     * Returns the amount of `prompt_tokens`.
     * 
     * @return int
     */
    public function getPromptTokens() {
        return $this->TokenUsage["prompt_tokens"];
    }


    /**
     * Returns the amount of `completion_tokens`.
     * 
     * @return int
     */
    public function getCompletionTokens() {
        return $this->TokenUsage["completion_tokens"];
    }


    /**
     * Returns the amount of `total_tokens`.
     * 
     * @return int
     */
    public function getTotalTokens() {
        return $this->TokenUsage["total_tokens"];
    }


    /**
     * Returns the finish reason.
     * 
     * @return string
     */
    public function getFinishReason() {
        return $this->finishReason;
    }

    /**
     * Returns the conversation id.
     * 
     * @return string
     */
    public function getConversationId() {
        return $this->ChatCompletionId;
    }


    /**
     * Starts the conversation.
     * 
     * @return ChatCompletions
     */
    public function startConversation() {

        // Prepend the system message to the beginning of
        // the messages thread
        array_unshift($this->MessageThread, [
            "role" => "system",
            "content" => $this->SystemMessage    
        ]);

        // Send the request
        $returnData = cURL::POST(
            $this->URL,
            [
                "model" => $this->Model,
                "messages" => $this->MessageThread,
                "max_tokens" => $this->MaxTokens
            ],
            ["Content-Type: application/json"]
        );

        // Check whether the result is `false`
        if (isset($returnData["error"]) === false) {
            $lastChoice = array_pop($returnData["choices"]);
            $this->MessageThread[] = $lastChoice["message"];
            $this->TokenUsage = $returnData["usage"];
            $this->finishReason = ($this->hasImages) ? $lastChoice["finish_details"]["type"] : $lastChoice["finish_reason"];
            $this->ChatCompletionId = $returnData["id"];
        }
        return $this;
    }
}