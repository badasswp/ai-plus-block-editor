# ai-plus-block-editor
Add AI Capabilities to the WP Block Editor.

[![Coverage Status](https://coveralls.io/repos/github/badasswp/ai-plus-block-editor/badge.svg?branch=master)](https://coveralls.io/github/badasswp/ai-plus-block-editor?branch=master)

<img width="446" alt="ai-plus-block-editor" src="https://github.com/user-attachments/assets/9e6dd4dd-1983-4723-9eb1-b3a77ac733ee">

## Download

Download from [WordPress plugin repository](https://wordpress.org/plugins/ai-plus-block-editor/).

You can also get the latest version from any of our [release tags](https://github.com/badasswp/ai-plus-block-editor/releases).

## Why AI Plus Block Editor?

Generate __Captions/Headlines__, __Summaries__, __Slugs__, __SEO Keywords__ using our amazing plugin. It is fast and very useful for users who need to quickly generate high-quality content with minimal effort. Whether you're a __blogger__, __editor__, or __content creator__, our plugin helps streamline your workflow by providing intelligent suggestions that enhance readability and SEO performance.

Save time and improve engagement with AI-powered insights directly within the WordPress block editor.

https://github.com/user-attachments/assets/93077898-ed42-4b48-b4e4-c3a2e23b55ac

| Headline | Slug   |
| :-       | :-     |
| <video src="https://github.com/user-attachments/assets/7d8fa09c-900c-49cb-a4d4-ed73419377ba"/> <br/><br/>| <video src="https://github.com/user-attachments/assets/a8d6df17-7c7d-49df-8a09-1c40b84e3487"/> <br/><br/>|
<br/>

| Keywords | Summary   |
| :-       | :-        |
| <video src="https://github.com/user-attachments/assets/e9b5a583-0573-4a8c-bd2c-47a5f8071e23"/> <br/><br/>| <video src="https://github.com/user-attachments/assets/7e9435b1-f505-48bf-a029-2889a669c67d"/> <br/><br/>|

## Getting Started

To get started, you would need to have an API key of your own provided by AI Provider for e.g. Open AI. Head over to the URL below and follow the instructions to generate your personal API key:

```
https://platform.openai.com/api-keys
```

If you have done this successfully, you should have save these details into your __AI + Block Editor__ options page and you are ready to go!

<img width="1388" alt="ai-plus-admin-options-page" src="https://github.com/user-attachments/assets/4b62a212-5935-45a7-a784-b0d7443ed70f" />

## Hooks

### PHP Hooks

#### `apbe_ai_provider`

This custom hook (filter) provides the ability to modify the AI Provider.

```php
use YourNamespace\OpenAI_Extender;

add_filter( 'apbe_ai_provider', [ $this, 'custom_ai_provider' ], 10, 1 );

public function custom_ai_provider( $ai_provider ) {
    if ( $ai_provider instanceOf OpenAI ) {
        $ai_provider = new OpenAI_Extender();
    }

    return $ai_provider;
}
```

**Parameters**

- ai_provider _`{Provider}`_ By default this will be an instance of the Provider interface that MUST contain a `run` method implementation.
<br/>

#### `apbe_open_ai_args`

This custom hook (filter) provides the ability to modify the OpenAI args before it sent to the LLM.

```php
add_filter( 'apbe_open_ai_args', [ $this, 'custom_args' ], 10, 1 );

public function custom_args( $args ) {
    return wp_parse_args(
        [
            'model'       => 'gpt-4-turbo',
            'temperature' => 1.5
            'max_tokens'  => 1000,
        ],
        $args
    )
}
```

**Parameters**

- args _`{array}`_ By default this will be an array containing the OpenAI default parameters.
<br/>

#### `apbe_tone_prompt`

This custom hook provides a simple way to filter the tone used by the AI LLM endpoint.

```php
add_filter( 'apbe_tone_prompt', [ $this, 'custom_tone' ], 10, 3 );

public function custom_tone( $prompt, $prompt_tone, $prompt_text ): string {
    if ( 'aggressive' === $prompt_tone ) {
        return sprintf(
            'Stay a bit official, but use an aggressive %s to generate text to replace %s',
            $prompt_tone,
            $prompt_text
        );
    }

    return $prompt;
}
```

**Parameters**

- prompt _`{string}`_ By default this will be a string that contains the __prompt__ sent to the AI LLM endpoint.
- prompt_tone _`{string}`_ By default this will be a string that contains the __tone__ sent to the AI LLM endpoint.
- prompt_text _`{string}`_ By default this will be a string that contains the __text__ sent to the AI LLM endpoint.
<br/>

#### `apbe_feature_prompt`

This custom hook provides a simple way to filter the feature prompt used by the AI LLM endpoint.

```php
add_filter( 'apbe_feature_prompt', [ $this, 'custom_feature' ], 10, 3 );

public function custom_feature( $prompt, $prompt_feature, $prompt_text ): string {
    if ( 'slug' === $prompt_feature ) {
        return sprintf(
            'Generate an SEO friendly %s using the content: %s',
            $prompt_feature,
            $prompt_text
        );
    }

    return $prompt;
}
```

**Parameters**

- prompt _`{string}`_ By default this will be a string that contains the __prompt__ sent to the AI LLM endpoint.
- prompt_feature _`{string}`_ By default this will be a string that contains the __feature__ sent to the AI LLM endpoint.
- prompt_text _`{string}`_ By default this will be a string that contains the __text__ sent to the AI LLM endpoint.
<br/>

#### `apbe_rest_routes`

This custom hook (filter) provides the ability to add and modify the default REST routes in the plugin.

```php
add_filter( 'apbe_rest_routes', [ $this, 'custom_route' ], 10, 1 );

public function custom_route( $rest_routes ) {
    if ( ! in_array( AiPostTitle::class, $rest_routes, true ) ) {
        $rest_routes[] = AiPostTitle::class;
    }

    return (array) $rest_routes;
}
```

**Parameters**

- rest_routes _`{array}`_ By default this will be an array containing the plugin's REST routes.
<br/>

#### `apbe_form_fields`

This custom hook (filter) provides the ability to add custom fields to the Admin options page like so:

```php
add_filter( 'apbe_form_fields', [ $this, 'custom_form_fields' ] );

public function custom_form_fields( $fields ): array {
    $fields = wp_parse_args(
        [
            'custom_group'  => [
                'label'    => 'Custom Heading',
                'controls' => [
                    'custom_option_1' => [
                        'control' => 'text',
                        'label'   => 'My Custom Option 1',
                        'summary' => 'Enable this option to save my custom option 1.',
                    ],
                    'custom_option_2' => [
                        'control' => 'select',
                        'label'   => 'My Custom Option 2',
                        'summary' => 'Enable this option to save my custom option 2.',
                        'options' => [],
                    ],
                    'custom_option_3' => [
                        'control' => 'checkbox',
                        'label'   => 'My Custom Option 3',
                        'summary' => 'Enable this option to save my custom option 3.',
                    ],
                ],
            ],
        ],
        $fields
    );

    return (array) $fields;
}
```

**Parameters**

- fields _`{array}`_ By default this will be an associative array containing key, value options of each field option.
<br/>

### JS Hooks

#### `apbe.allowedBlocks`

This custom hook (filter) provides the ability to extend the AiTone feature to other custom blocks:

```js
import { addFilter } from '@wordpress/hooks';

addFilter(
	'apbe.allowedBlocks',
	'yourBlocks',
	( allowedBlocks ) => {
		if ( allowedBlocks.indexOf( 'your/block' ) === -1 ) {
			allowedBlocks.push( 'your/block' );
		}

		return allowedBlocks;
	}
);
```

**Parameters**

- allowedBlocks _`{string[]}`_ List of Allowed Blocks.
<br/>

#### `apbe.blockMenuOptions`

This custom hook (filter) provides the ability to extend the menu options shown when using the AI tone feature on a block:

```js
import { addFilter } from '@wordpress/hooks';

addFilter(
	'apbe.blockMenuOptions',
	'yourBlockMenuOptions',
	( blockMenuOptions ) => {
		const yourOptions = {
			conversation: __( 'Use Conversation Tone', 'ai-plus-block-editor' )
		}

		return { ...blockMenuOptions, ...yourOptions }
	}
);
```

**Parameters**

- blockMenuOptions _`{object}`_ List of Block Menu Options.
<br/>

---

## Contribute

Contributions are __welcome__ and will be fully __credited__. To contribute, please fork this repo and raise a PR (Pull Request) against the `master` branch.

### Pre-requisites

You should have the following tools before proceeding to the next steps:

- Composer
- Yarn
- Docker

To enable you start development, please run:

```bash
yarn start
```

This should spin up a local WP env instance for you to work with at:

```bash
http://apbe.localhost:5487
```

You should now have a functioning local WP env to work with. To login to the `wp-admin` backend, please use `admin` for username & `password` for password.

__Awesome!__ - Thanks for being interested in contributing your time and code to this project!
