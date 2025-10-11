=== AI + Block Editor ===
Contributors: badasswp
Tags: ai, block, editor, chat-gpt, assistant.
Requires at least: 6.0
Tested up to: 6.8
Stable tag: 1.8.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add AI Capabilities to the Block Editor. Generate Captions/Headlines, Summaries, Slugs, SEO Keywords using our amazing plugin.

== Installation ==

1. Go to <strong>Plugins > Add New</strong> on your WordPress admin dashboard.
2. Search for <strong>AI + Block Editor</strong> plugin from the official WordPress plugin repository.
3. Click 'Install Now' and then 'Activate'.
4. To get started, you would need to have an API key from your API Provider. For e.g. Open AI is <a href="https://platform.openai.com/api-keys">https://platform.openai.com/api-keys</a>.
5. If you have gotten your API key, you should save it into your plugin options page.
6. Make sure to enable the checkbox for your API Provider for e.g. <strong>Enable Open AI</strong> or any other as applicable to you.
7. That's it! You're all set to start using AI.

== Description ==

Add AI Capabilities to the Block Editor.

Generate <strong>Captions/Headlines</strong>, <strong>Summaries</strong>, <strong>Slugs</strong>, <strong>SEO Keywords</strong> using our amazing plugin. It is fast and very useful for users who need to quickly generate high-quality content with minimal effort. Whether you're a <strong>blogger</strong>, <strong>editor</strong>, or <strong>content creator</strong>, our plugin helps streamline your workflow by providing intelligent suggestions that enhance readability and SEO performance.

Save time and improve engagement with AI-powered insights directly within the WordPress block editor.

= ✔️ Features =

Our plugin comes with everything you need to add AI capabilities to your Block Editor.

✔️ <strong>Support for LLMs</strong> such as <strong>ChatGPT, Gemini, Deepseek, Grok, Claude</strong> and a host of others.
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

1. To get started, you would need to have an API key from your API Provider. For e.g. Open AI is <a href="https://platform.openai.com/api-keys">https://platform.openai.com/api-keys</a>.

2. If you have gotten your API key, you should save it into your plugin options page.

3. Make sure to enable the checkbox for your API Provider for e.g. <strong>Enable Open AI</strong> or any other as applicable to you.

You can get a taste of how this plugin works, by using the [demo](https://tastewp.com/create/NMS/8.0/6.7.0/ai-plus-block-editor/twentytwentythree?ni=true&origin=wp) link.

= 🔌🎨 Plug and Play or Customize =

The AI Plus Block Editor plugin is built to work right out of the box. Simply install, activate and start using.

Want to add your personal touch? All of our documentation can be found [here](https://github.com/badasswp/ai-plus-block-editor). You can override the plugin's behaviour with custom logic of your own using [hooks](https://github.com/badasswp/ai-plus-block-editor?tab=readme-ov-file#hooks).

== Screenshots ==

1. Change Text Tone - AI Capabilities added to the Block editor's Toolbar.
2. Headline, Summary, Slug, Keywords - AI Capabilities added to the Block editor's Sidebar.
3. Options Page - Add API keys and select AI Provider.
4. Sidebar Icon - Locate AI Sidebar feature on the top-right corner of screen.

== Changelog ==

= 1.8.0 =
* Feat: Implement Claude AI provider.
* Fix: Load missing Grok provider correctly.
* Feat: Implement AI provider abstraction.
* Refactor: Improve AI providers.
* Feat: Enable filtering of AI Switcher options.
* Feat: Add error logging capabilities.
* Chore: Update plugin menu icon.
* Feat: Add custom filters:
  - `apbe_ai_provider_success_call`.
  - `apbe_ai_provider_fail_call`.
  - `apbe_ai_provider_response`.
  - `apbe_ai_providers`.
  - `apbe_claude_api_url`.
  - `apbe_claude_args`.
  - `apbe_claude_system_prompt`.
  - `apbe_deepseek_system_prompt`.
  - `apbe_open_ai_system_prompt`.
* Docs: Update README docs.
* Test: Update unit tests.
* Tested up to WP 6.8.2.

= 1.7.1 =
* Fix: Update issue with broken plugin deploy.
* Docs: Update README docs.

= 1.7.0 =
* Feat: Add Grok AI provider.
* Feat: Implement custom filters `apbe_grok_api_url`, `apbe_grok_args`, `apbe_grok_system_prompt`.
* Test: Update unit test cases.
* Docs: Update README docs.
* Tested up to WP 6.8.

= 1.6.2 =
* Update CI/CD build workflow.
* Tested up to WP 6.8.2.

= 1.6.1 =
* Upgrade plugin version.
* Tested up to WP 6.8.2.

= 1.6.0 =
* Refactor: Update Components to use WP's snackbar notice.
* Feat: Implement DeepSeek AI provider.
* Feat: Implement custom filters `apbe_deepseek_api_url`, `apbe_deepseek_args`.
* Fix: Console errors.
* Tested up to WP 6.8.

= 1.5.0 =
* Feat: AI Provider Switcher.
* Feat: Add Google Gemini AI provider.
* Feat: Implement custom filters `apbe_gemini_api_url`, `apbe_gemini_args`.
* Feat: Add Social media hash-tag sidebar feature.
* Feat: Update text translations for new features.
* Feat: Improve AI prompts.
* Fix: Breaking issue with Tone functionality.
* Test: Fix Unit tests & update snapshots.
* Chore: Relocate `apbe.blockMenuOptions` hook.
* Fix: Console errors in unit tests.

= 1.4.0 =
* Refactor: Adopt useSelect & useDispatch hooks in sidebar components.
* Feat: Add new language translations for Italian, Russian, Chinese, Arabic, Hebrew & Croatian.
* Feat: Add new custom filters `apbe.allowedBlocks`.
* Test: Update Unit Tests to match refactor work.
* Update README docs.
* Tested up to WP 6.7.2.

= 1.3.0 =
* Feat: Update translations for French, Danish, Spanish & German languages.
* Feat: Add new toast for AiTone usage.
* Fix: Resolve issue with `createErrorNotice` for AiTone.
* Fix: Update WP bash scripts.
* Fix: Update README notes.
* Tested up to WP 6.7.2.

= 1.2.0 =
* Feat: Add local development environment setup.
* Fix: Gracefully deal with WP REST error responses.
* Fix: AI sidebar feature save buttons not working correctly.
* Fix: Deal with the issue of super-imposed notices.
* Chore: Enforce WP linting style across plugin.
* Tested up to WP 6.7.2.

= 1.1.2 =
* Refactor: AI client instance, make interchangeable.
* Fix: Minor syntax typos.
* Chore: Updated README notes.
* Test: Add more unit tests.
* Tested up to WP 6.7.1.

= 1.1.1 =
* Fix: Resolved issues with onChange handler for AiSidebar fields.
* Fix: Resolved issues with front slashed slug.
* Fix: Resolved issue with deprecation warnings - nextHasNoMarginBottom.
* Fix: Resolved issue related to null object on: editor-post-title update.
* Fix: Resolved issue with dependency conflict `wp-edit-site`.
* Chore: Updated build ignore list.
* Chore: Updated README notes.
* Tested up to WP 6.7.1.

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
