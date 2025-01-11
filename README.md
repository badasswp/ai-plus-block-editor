# ai-plus-block-editor
Add AI Capabilities to the WP Block Editor.

## Download

Download from [WordPress plugin repository](https://wordpress.org/plugins/ai-plus-block-editor/).

You can also get the latest version from any of our [release tags](https://github.com/badasswp/ai-plus-block-editor/releases).

## Why AI Plus Block Editor?

This plugin adds AI capabilities to the Block Editor.

https://github.com/user-attachments/assets/93856ac3-602d-4f3d-b769-777ef27d5725

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
