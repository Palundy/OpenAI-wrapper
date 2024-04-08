# OpenAI PHP Wrapper

This repository provides a PHP wrapper for OpenAI GPT-3 and GPT-4 APIs. The classes here are designed to make it easy to generate text using the OpenAI API, from setting up a conversation to retrieving the conversation and then storing it.

## Usage

To use this wrapper, first import the necessary files:

```php
include_once("OpenAI.php");
```

Then declare a new instance of the `ChatCompletions` class and set up the system message:

```php
$Chat = OpenAI::ChatCompletions();
$Chat->setSystemMessage("You are a helpful assistant.");
```

You can then add user messages and any necessary settings:

```php
// Add a user message
$Chat->addUserMessage("Can you help me write a novel about the following topics?");
$Chat->addUserMessage("- World War 2");
$Chat->addUserMessage("- The Cambrian Explosion");

// Change settings
$Chat->setMaxTokens(1000);         // Set the maximum amount of tokens
$Chat->storeConversation(true);  // Store the conversation
```

Finally, you can start the conversation and display a crude version of the conversation:

```php
// Start the conversation and display it
$Chat->startConversation();
$Chat->showConversation();
```

The `storeConversation(true)` method allows you to store your conversations. To later retrieve the same conversation:

```php
// Retrieve the conversation id 
$ConversationId = $Chat->getConversationId();
```

Then you can use the saved `ConversationId` to retrieve the conversation:

```php
$Chat = OpenAI::ChatCompletions($ConversationId);
```

This wrapper also allows for the addition of images into the conversation. To add an image:

```php
// Add an image
$Chat->addImage("https://example.com/path-to-your-image.jpg");
```

## Conclusion

The `ChatCompletions` class is the main class you will be interfacing with. It provides methods to set up conversations, add user and assistant messages, and store and retrieve conversations. Use the examples given above as a template for your own applications.

Enjoy coding!
