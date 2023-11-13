# OpenAI-wrapper

OpenAI-wrapper is a simple PHP wrapper for interacting with [OpenAI's API](https://beta.openai.com/docs/), specifically designed to work with chat completions utilizing models such as GPT-4. This wrapper simplifies the process of sending messages, starting conversations, storing threads, and handling image inputs in the context of an AI-driven chat environment.

## Installation

To use OpenAI-wrapper, clone this repository or download its contents into your project directory.

```
git clone https://github.com/your-username/OpenAI-wrapper.git
```

Make sure you have PHP installed on your server or development environment.

## Configuration

**Option 1:**
1. Obtain an API key from [OpenAI](https://beta.openai.com/signup/).
2. Insert your API key into the following line in the `OpenAI.php` file:
```
private static $API_KEY = "YOUR_API_KEY";
```
**Option 2:**
1. Obtain an API key from [OpenAI](https://beta.openai.com/signup/).
2. Create a `.env` file in the root directory of this project.
3. Add the following line to your `.env` file, replacing `YOUR_API_KEY` with the key you got from OpenAI.

```
API_KEY=YOUR_API_KEY
```

## Basic Usage

Here’s how to get started with the OpenAI-wrapper.

### Include the `OpenAI` class

Start by including the `OpenAI` class in your PHP script.

```php
require_once 'path/to/OpenAI.php';
```

### Initializing a Chat Session

```php
// Start a new chat session
$Chat = OpenAI::ChatCompletions();
```

If you want to continue an existing thread, pass the conversation ID as an argument:

```php
// Continue an existing chat session
$Chat = OpenAI::ChatCompletions('CONVERSATION_ID_HERE');
```

### Sending Messages & Interacting with the Chat

Add messages from the user and the assistant and start the conversation.

```php
$Chat->setSystemMessage("You are a helpful assistant.");
$Chat->addUserMessage("What's the weather like in London?")->startThread();
```

To get the last response from the chat, use:

```php
$Response = $Chat->getResponse();
echo $Response; // Outputs the response from the conversation
```

Or show a crude output of the complete message thread:

```php
$Chat->showThread();
```

### Storing Chat Threads

By default, conversations are not stored. If you want to keep a record of conversations, enable storage:

```php
$Chat->storeThread(true);
```

### Adding Images

You can also handle image inputs by appending an image URL to the conversation.

```php
$Chat->addImage('https://example.com/path/to/image.jpg');
```

## Advanced Configuration

### Using a Different Model

To use a different model, such as `'gpt-3.5-turbo'`, set it before starting the thread:

```php
$Chat->setModel('gpt-3.5-turbo');
```

Note: Image inputs can only be handled by specific image-enabled models such as `gpt-4-vision-preview`.
If an image is appended to the chat with `$Chat->addImage()` the model will be set to `gpt-4-vision-preview` automatically.

### System Message

You can set a system message that will be prepended to the conversation,
this acts as the role of the system:

```php
$Chat->setSystemMessage('You are conversing with an AI.');
```

## Example Script 1

```php
require_once 'path/to/OpenAI.php';

// Initialize the chat
$Chat = OpenAI::ChatCompletions();

// Configure the chat session
$Chat->setSystemMessage("You are a professional novel writer.")
     ->addUserMessage("Help me write a novel about: World War 2 and the Cambrian Explosion.")
     ->storeThread(true)->setMaxTokens(750)
     ->startThread();

// Display the last response
echo $Chat->getResponse();
```

## Example Script 2

```php
require_once 'path/to/OpenAI.php';

// Initialize the chat
$Chat = OpenAI::ChatCompletions();

// Configure the chat session
$Chat->setSystemMessage("You are a helpful assistant")
     ->addUserMessage("What is displayed on the following image?")
     ->addImage("https://en.wikipedia.org/wiki/File:The_Matrix_Poster.jpg")
     ->addUserMessage("And can you give me a summary of the Matrix triology using the following URL: https://en.wikipedia.org/wiki/The_Matrix) // Use the cURL::GET() function to retrieve the content of a page
     ->startThread();

// Display the last response
echo $Chat->getResponse();
```

## License

This project is licensed under the MIT License - see the LICENSE.md file for details.

## Contributions

Contributions are welcome! Please submit pull requests or create issues for bugs and feature requests.

## Support

For support using OpenAI's API, refer to the official [OpenAI API Documentation](https://beta.openai.com/docs/).

For issues related to this wrapper, please use the GitHub issue tracker associated with this repository.

---

`Note: This wrapper is not officially associated with OpenAI. It is a third-party implementation designed to simplify certain aspects of interfacing with OpenAI's API.`
