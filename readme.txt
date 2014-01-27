=== WP Qore ===
Contributors: phatjay, keha76, davejesch
Tags: developer, security, performance, antivirus, virus, malware, cache, tools, qore, core, customize login page, hide login page, security advisor, replace dashboard, custom dashboard, disable nag updates, disable core updates, admin bar removal, collapse admin menu, use jquery cdn, shortcode in widget, php in widgets, export widgets, import widgets, remove wp version, minify your html, gzip compression, cleanup wp meta, new theme directory, unique source code, automatic updates, admin bar opacity
Requires at least: 3.3
Tested up to: 3.8.1
Stable tag: 4.8
License: GNU GPL 3.0
License URI: http://www.gnu.org/licenses/gpl.html

WordPress Security, Developer Tools Plugin.

== Description ==

<a target="_blank" href="http://wpqore.com/">WP Qore</a>, a plugin that provides additional security, performance functionality, and developer tools that can be turned on or off at any time. 

WP Qore offers many powerful features such as Security Advisor, which is our malware and anti-virus scanner. Protection for your WordPress website. Backed by updates from Google's database of web misfits. Rest assured you're always up-to-date and secured.

WP Qore, first debut in the WordPress.org plugin repository is v1.1.4 and previous versions are maintained on <a target="_blank" href="https://github.com/icryptic/wp-qore/">Github</a>.

Project lead: Jason Jersey

Available Options: (toggle on/off)

* customize login page
* hide login page
* security advisor
* replace dashboard
* custom dashboard
* automatic updates
* disable nag updates
* disable core updates
* admin bar removal
* admin bar opacity
* collapse admin menu
* use jquery cdn
* shortcode in widget
* php in widgets
* import/export widgets
* dropbox backup
* remove wp version
* minify your html
* cleanup wp meta
* new theme directory
* unique source code

Langauage Support:

* English (standard)
* Español/México (es_MX)
* French/France (fr_FR)
* Svenska/Sverige (sv_SE)
* Norwegian (no_NO)

== Installation ==

This will activate the WP Qore WordPress Plugin.

1. Upload `wp-qore` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust options within 'WP Qore' admin menu

Once activated, please update your options via the WP Qore menu located within the left side of the wp-admin.

== Frequently Asked Questions ==

= How do you see this plugin differentiating itself from others like Better WP Security, Hide My Login, or W3TC?  =

I can't say this plugin compares with the others. It wasn't created to compete with others. Truthfully, this plugin was created for myself. The functionality the plugin provides I use often with my own and even client sites. So I basically created a lightweight plugin that would serve the purpose of a way to turn on and off certain functionality preferably security and performance related things.

= Do you have a Github repository, too? =

Yes. I currently have WP Qore on Github. You may find the project here: https://github.com/icryptic/wp-qore

== Screenshots ==

1. WP Qore Dashboard

== Changelog ==

= 1.8.4 = 
* Fixed bug in Posts and Comments icon.

= 1.8.3 = 
* Fixed Timeout in wpqore_foldersize(). Fix by Dave Jesch.

= 1.8.2 = 
* Applied patch to howdy.php.
* Added Norwegian translation.
* Updated other language files.

= 1.8.1 = 
* Fixed missing textdomain and updated Swedish translation.

= 1.8.0 = 
* Oops, quick howdy fix.

= 1.7.9 = 
* Added option to customize Howdy text.

= 1.7.8 = 
* Added WP Qore dashboard section options.

= 1.7.7 = 
* Added frontend wp-admin bar opacity.

= 1.7.6 = 
* Added hacker teaser 404 page for login conceal feature.

= 1.7.5 = 
* Updated language strings.
* Updated language files.

= 1.7.4 = 
* Merged pull from PlanetZuda, minor code fixes.

= 1.7.3 = 
* Disabled Cache Assistance. A new stable version is in the works.

= 1.7.2 = 
* Fixed admin-bar link

= 1.7.1 = 
* Updated Dropbox Authorize panel.
* Updated language files.

= 1.7.0 = 
* Added Dropbox backup module.

= 1.6.9 = 
* Added notice for Cache Assistance.
* Temp adjusted Cache Assistance default settings.

= 1.6.8 = 
* Added enhancements to dashboard.

= 1.6.7 = 
* Removed event.returnValue and unused javascript.

= 1.6.6 =
* Fixed issue with shared hosts blocking exec().

= 1.6.5 =
* Fixed Speedtest function (check if Windows or Linux). If is Windows, currently returns a zero. This is minor and will be corrected later on.

= 1.6.4 =
* Fixed errors in translation files.

= 1.6.3 =
* Added two footer blocks to the dashboard shown to users with the manage_options user role.

= 1.6.2 =
* Updated dashboard sections.

= 1.6.1 =
* Added user roles to dashboard.

= 1.6.0 =
* Added new wp-admin dashboard.

= 1.5.9 =
* Removed dash_tabs function in preperation for 1.6.

= 1.5.8 =
* Added automatic updater options.

= 1.5.7 =
* Fixed TinyMCE from modifying html when changing tabs.

= 1.5.6 =
* Added custom dashboard option.

= 1.5.5 =
* Added Español/México (es_MX) language support.

= 1.5.4 =
* Added French (fr_FR) language support.

= 1.5.3 =
* Fixed NoCache Button for Posts and Pages.

= 1.5.2 =
* Fixed bug associated with Add_Editor_Button() within posts.

= 1.5.1 =
* Fixed bug: Cache would not delete for pages when a change was being made to pages. This is resolved..

= 1.5.0 =
* Swedish translation by Kenth Hagström.

= 1.4.9 =
* Localization added by Kenth Hagström.

= 1.4.8 =
* Updated dashboard.

= 1.4.7 =
* CSS fix for options panals.

= 1.4.6 =
* Disabled notice on login secret arg.

= 1.4.5 =
* Fixed wpqore options panels for 3.8 RC 2.

= 1.4.4 =
* Deprecated the force_ssl_admin function. Decided the plugin was not really the best way to approach it.

= 1.4.3 =
* Latest release.

= 1.4.2 =
* Added WP Qore to admin bar.

= 1.4.1 =
* Added option to keep wp-admin menu folded.

= 1.4.0 =
* Added Cache Assistance tab to dashboard.
* Removed gzip option. Already handled by cache.

= 1.3.9 =
* Added frontend caching system.

= 1.3.8 =
* Replaced deprectaed function in Database Audit.
* Removed prepare() from 2 lines within the functions.php file.
* Resolved all debug notices.

= 1.3.7 =
* Removed load_plugin_lang in sec-advisor.php.

= 1.3.6 =
* Added activation and deactivation hook to index.php.

= 1.3.5 =
* Updated settings.php

= 1.3.4 =
* Added example screenshot to the WP Qore settings panel for Dashboard Tabs.

= 1.3.3 =
* Added option to WP Qore settings panel to conceal the WP Qore tabs from within the dashboard_view.php.

= 1.3.2 =
* Added tabs to dashboard_view.php.

= 1.3.1 =
* Minor changes. Cleaned up sec-advisor.php file.

= 1.3.0 =
* Added Security Advisor. Security Advisor offers you protection from security threats, such as: virus, malicious code, and security exploits.

= 1.2.0 =
* Bug in commit for 1.1.9 caused core-update.php to not reach the wp repo. This update is to solve that issue.

= 1.1.9 =
* Settings.php UI update.

= 1.1.8 =
* Added option to disable core updates. Used for dev sites.
* Fixed spelling typo within settings.php.
* Added option to disable post revisions.
* Added option to force wp-admin ssl connection.

= 1.1.7 =
* Replaced CSS import with enqueue for both dashboard and WP Qore admin options page.

= 1.1.6 =
* Removed dashboard icon opacity css.
* Added push button effect css to dashboard icons.

= 1.1.5 =
* Replaced dashboard icons.
* Fixed dashboard css (adjusted icon opacity).

= 1.1.4 =
* Debut version in WordPress.org plugin repository.

= 1.1.3 =
* Deprecated submenu item > login-security.php. 

= 1.1.2 =
* Deprecated WordPress Admin Bootstrap.

= 1.1.1 =
* Replaced checkboxes with on/off toggle buttons.

= 1.1.0 =
* Moved import/export widgets to the wp-admin > Tools menu via add_admin_menus().

= 1.0.9 =
* Updated code to use prepare() with queries to protect them from sql injections.

= 1.0.8 =
* Visual fix. Added two line breaks on db audit panel.

= 1.0.7 =
* Disabled calling wp-load directly on line 4.

= 1.0.6 =
* Replaced deprecated function in functions.php, has_cap to manage_options.

= 1.0.5 =
* Fixed minor bug with import/export widgets

= 1.0.4 =
* Enabled import and exporting of widgets. 
* Fixed small bug in functions.php pertaining to if{shortcode}.

= 1.0.3 =
* Enabled php in widgets. 
* Updated project description.

= 1.0.2 =
* Deprecated self-hosted plugin updater in preparation for the WordPress.org repository.

= 1.0.1 =
* Fixed a bug in updater. This was pre-wp repo.

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.8.4 =
* Upgrade for the latest version.

= 1.8.3 =
* Upgrade for the latest version.

= 1.8.2 =
* Upgrade for the latest version.

= 1.8.1 =
* Upgrade for the latest version.

= 1.8.0 =
* Upgrade for the latest version.

= 1.7.9 =
* Upgrade for the latest version.

= 1.7.8 =
* Upgrade for the latest version.

= 1.7.7 =
* Upgrade for the latest version.

= 1.7.6 =
* Upgrade for the latest version.

= 1.7.5 =
* Upgrade for the latest version.

= 1.7.4 =
* Upgrade for the latest version.

= 1.7.3 =
* Upgrade for the latest version.

= 1.7.2 =
* Upgrade for the latest version.

= 1.7.1 =
* Upgrade for the latest version.

= 1.7.0 =
* Upgrade for the latest version.

= 1.6.9 =
* Upgrade for the latest version.

= 1.6.8 =
* Upgrade for the latest version.

= 1.6.7 =
* Upgrade for the latest version.

= 1.6.6 =
* Upgrade for the latest version.

= 1.6.5 =
* Upgrade for the latest version.

= 1.6.4 =
* Upgrade for the latest version.

= 1.6.3 =
* Upgrade for the latest version.

= 1.6.2 =
* Upgrade for the latest version.

= 1.6.1 =
* Upgrade for the latest version.

= 1.6.0 =
* Upgrade for the latest version.

= 1.5.9 =
* Upgrade for the latest version.

= 1.5.8 =
* Upgrade for the latest version.

= 1.5.7 =
* Upgrade for the latest version.

= 1.5.6 =
* Upgrade for the latest version.

= 1.5.5 =
* Upgrade for the latest version.

= 1.5.4 =
* Upgrade for the latest version.

= 1.5.3 =
* Upgrade for the latest version.

= 1.5.2 =
* Upgrade for the latest version.

= 1.5.1 =
* Upgrade for the latest version.

= 1.5.0 =
* Upgrade for the latest version.

= 1.4.9 =
* Upgrade for the latest version.

= 1.4.8 =
* Upgrade for the latest version.

= 1.4.7 =
* Upgrade for the latest version.

= 1.4.6 =
* Upgrade for the latest version.

= 1.4.5 =
* Upgrade for the latest version.

= 1.4.4 =
* Upgrade for the latest version.

= 1.4.3 =
* Upgrade for the latest version.

= 1.4.2 =
* Upgrade for the latest version.

= 1.4.1 =
* Upgrade for the latest version.

= 1.4.0 =
* Upgrade for the latest version.

= 1.3.9 =
* Upgrade for the latest version.

= 1.3.8 =
* Upgrade for the latest version.

= 1.3.7 =
* Upgrade for the latest version.

= 1.3.6 =
* Upgrade for the latest version.

= 1.3.5 =
* Upgrade for the latest version.

= 1.3.4 =
* Upgrade for the latest version.

= 1.3.3 =
* Upgrade for the latest version.

= 1.3.2 =
* Upgrade for the latest version.

= 1.3.1 =
* Upgrade for the latest version.

= 1.3.0 =
* Upgrade for the latest version.

= 1.2.0 =
* Upgrade for the latest version.

= 1.1.9 =
* Upgrade for the latest version.


= 1.1.8 =
* Upgrade for the latest version.

= 1.1.7 =
* Upgrade for the latest version.

= 1.1.6 =
* Upgrade for the latest version.

= 1.1.5 =
* Upgrade for the latest version.

= 1.1.4 =
* Upgrade for the latest version.

= 1.1.3 =
* Upgrade to fix possible security vulnerbility.

= 1.1.2 =
* Upgrade to fix possible security vulnerbility.

= 1.1.1 =
* Upgrade to add cosmetic changes.

= 1.1.0 =
* Upgrade to fix minor bug in export widgets.

= 1.0.9 =
* Upgrade to fix possible security vulnerbility.

= 1.0.8 =
* Fix to Database Optimization Audit panel.

= 1.0.7 =
* Upgrade to fix possible security vulnerbility.

= 1.0.6 =
* Upgrade to fix deprecated function in functions.php.

= 1.0.5 =
* Upgrade to fix minor bug with import/export widgets.

= 1.0.4 =
* Upgrade adds new functionality.

= 1.0.3 =
* Upgrade adds new functionality.

= 1.0.2 =
* Upgrade now for the latest version.

= 1.0.1 =
* Upgrade now to make sure you do not miss any future updates. This update fixes the WordPress updater to notify users upon new version upgrades.
