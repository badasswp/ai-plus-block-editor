# ai-plus-block-editor
Add AI Capabilities to the WP Block Editor.

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
<br/>

### Hooks

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
add_filter( 'apbe_rest_routes', [ $this, 'custom_route' ], 10, 3 );

public function custom_rest_routes( $rest_routes ) {
    if ( ! in_array( AiPostTitle::class, $rest_routes, true ) ) {
        $rest_routes[] = AiPostTitle::class;
    }

    return (array) $rest_routes;
}
```

**Parameters**

- rest_routes _`{array}`_ By default this will be an array containing the plugin's REST routes.
<br/>

