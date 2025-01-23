=== AI + Block Editor ===
Contributors: badasswp
Tags: ai, block, editor, chat-gpt, assistant.
Requires at least: 6.0
Tested up to: 6.7.1
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add AI Capabilities to the Block Editor.

Generate <strong>Captions/Headlines</strong>, <strong>Summaries</strong>, <strong>Slugs</strong>, <strong>SEO Keywords</strong> using our amazing plugin. It is fast and very useful for users who need to quickly generate high-quality content with minimal effort. Whether you're a <strong>blogger</strong>, <strong>editor</strong>, or <strong>content creator</strong>, our plugin helps streamline your workflow by providing intelligent suggestions that enhance readability and SEO performance.

Save time and improve engagement with AI-powered insights directly within the WordPress block editor.

== Installation ==

1. Go to 'Plugins > Add New' on your WordPress admin dashboard.
2. Search for 'AI + Block Editor' plugin from the official WordPress plugin repository.
3. Click 'Install Now' and then 'Activate'.
4. To get started, you would need to have an API key from your API Provider (for e.g. Open AI). Visit any of the AI provider sites to get one.
5. If you have gotten your API key, you should save it into your plugin options page.
6. Make sure to enable the checkbox for your API Provider for e.g. <strong>Enable Open AI</strong or any other as applicable to you.

== Description ==

Add AI Capabilities to the Block Editor.

= ✔️ Features =

Our plugin comes with everything you need to add AI capabilities to your Block Editor.

✔️ <strong>Change Text Tone (casual, official, narrative, and so on...)</strong> in block editor.
✔️ <strong>Generate Title/Headline</strong> using AI.
✔️ <strong>Generate Summary/Excerpts</strong>.
✔️ <strong>Generate SEO friendly URL Slugs</strong> for your post articles.
✔️ <strong>Generate SEO Keywords</strong> to help with SEO optimisation.
✔️ <strong>Custom Filters</strong> to help user <strong>modify AI prompts</strong>.
✔️ <strong>Shortcut Keys</strong> - CMD + SHIFT + A + I.
✔️ Available in <strong>mutiple langauges</strong> such as Arabic, Chinese, Hebrew, Hindi, Russian, German, Italian, Croatian, Spanish & French languages.
✔️ <strong>Backward compatible</strong>, works with most WP versions.

= ✨ Getting Started =

1. To get started, you would need to have an API key from your API Provider (for e.g. Open AI). Visit any of the AI provider sites to get one.

2. If you have gotten your API key, you should save it into your plugin options page.

3. Make sure to enable the checkbox for your API Provider for e.g. <strong>Enable Open AI</strong> or any other as applicable to you.

You can get a taste of how this works, by using the [demo](https://tastewp.com/create/NMS/8.0/6.7.0/ai-plus-block-editor/twentytwentythree?ni=true&origin=wp) link.

= 🔌🎨 Plug and Play or Customize =

The AI Plus Block Editor plugin is built to work right out of the box. Simply install, activate and start using.

Want to add your personal touch? All of our documentation can be found [here](https://github.com/badasswp/ai-plus-block-editor). You can override the plugin's behaviour with custom logic of your own using [hooks](https://github.com/badasswp/ai-plus-block-editor?tab=readme-ov-file#hooks).

== Screenshots ==

1. Change Text Tone - AI Capabilities added to the Block editor's Toolbar.
2. Headline, Summary, Slug, Keywords - AI Capabilities added to the Block editor's Sidebar.
3. Options Page - Add API keys and select AI Provider.
4. Sidebar Icon - Locate AI Sidebar feature on the top-right corner of screen.

== Changelog ==

= 1.1.0 =
* Feat: Added Headline, Summary, Slug, Keywords feature.
* Feat: Added AI sidebar.
* Feat: Introduced Post meta features for Articles.
* Feat: Implemented custom filters `apbe_tone_prompt`, `apbe_feature_prompt` & `apbe_rest_routes`.
* Tested up to WP 6.7.1.

= 1.0.1 =
* Reference assets directly using `plugin_dir_url`.
* Upgrade CI/CD build to Node 20.
* Update custom filter prefix `apbe_form_fields`.
* Tested up to WP 6.7.1.

= 1.0.0 =
* Add AI tone capabilities to toolbar.
* Add custom plugin options page.
* Add Unit Tests.
* Update README notes.
* Tested up to WP 6.7.1.

== Contribute ==

If you'd like to contribute to the development of this plugin, you can find it on [GitHub](https://github.com/badasswp/ai-plus-block-editor).

To build, clone repo and run `yarn install && yarn build`
