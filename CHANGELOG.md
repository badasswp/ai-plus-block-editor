# Changelog

# 1.6.2
* Update CI/CD build workflow.
* Tested up to WP 6.8.2.

# 1.6.1
* Upgrade plugin version.
* Tested up to WP 6.8.2.

# 1.6.0
* Refactor: Update Components to use WP's snackbar notice.
* Feat: Implement DeepSeek AI provider.
* Feat: Implement custom filters `apbe_deepseek_api_url`, `apbe_deepseek_args`.
* Fix: Console errors.
* Tested up to WP 6.8.

# 1.5.0
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

# 1.4.0
* Refactor: Adopt useSelect & useDispatch hooks in sidebar components.
* Feat: Add new language translations for Italian, Russian, Chinese, Arabic, Hebrew & Croatian.
* Feat: Add new custom filters `apbe.allowedBlocks`.
* Test: Update Unit Tests to match refactor work.
* Update README docs.
* Tested up to WP 6.7.2.

# 1.3.0
* Feat: Update translations for French, Danish, Spanish & German languages.
* Feat: Add new toast for AiTone usage.
* Fix: Resolve issue with `createErrorNotice` for AiTone.
* Fix: Update WP bash scripts.
* Fix: Update README notes.
* Tested up to WP 6.7.2.

## 1.2.0
* Feat: Add local development environment setup.
* Fix: Gracefully deal with WP REST error responses.
* Fix: AI sidebar feature save buttons not working correctly.
* Fix: Deal with the issue of super-imposed notices.
* Chore: Enforce WP linting style across plugin.
* Tested up to WP 6.7.2.

## 1.1.2
* Refactor: AI client instance, make interchangeable.
* Fix: Minor syntax typos.
* Chore: Updated README notes.
* Test: Add more unit tests.
* Tested up to WP 6.7.1.

## 1.1.1
* Fix: Resolved issues with onChange handler for AiSidebar fields.
* Fix: Resolved issues with front slashed slug.
* Fix: Resolved issue with deprecation warnings - nextHasNoMarginBottom.
* Fix: Resolved issue related to null object on: editor-post-title update.
* Fix: Resolved issue with dependency conflict `wp-edit-site`.
* Chore: Updated build ignore list.
* Chore: Updated README notes.
* Tested up to WP 6.7.1.

## 1.1.0
* Feat: Added Headline, Summary, Slug, Keywords feature.
* Feat: Added AI sidebar.
* Feat: Introduced Post meta features for Articles.
* Feat: Implemented custom filters `apbe_tone_prompt`, `apbe_feature_prompt` & `apbe_rest_routes`.
* Tested up to WP 6.7.1.

## 1.0.1
* Reference assets directly using `plugin_dir_url`.
* Upgrade CI/CD build to Node 20.
* Update custom filter prefix `apbe_form_fields`.
* Tested up to WP 6.7.1.

## 1.0.0 (Initial Release)
* Add AI tone capabilities to toolbar.
* Add custom plugin options page.
* Add Unit Tests.
* Update README notes.
* Tested up to WP 6.7.1.
