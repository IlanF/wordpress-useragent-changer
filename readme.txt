=== Default Useragent Changer ===
Contributors: ilanfir
Tags: http, useragent, request, get, post
Requires at least: 4.0.0
Tested up to: 4.5.3
Stable tag: trunk
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows to set a custom useragent and override the default one WordPress is using for HTTP requests.

== Description ==

Sometimes the default useragent WordPress is using will not work for you. Either the server you contact filters requests based on useragent or you just wan to identifiy as a different application. Default Useragent Changer will allow you to change the default useragent with ease.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/firsov-user-agent-changer` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Configure the required useragent string at Settings->General screen or leave it blank to restore the default useragent

== Frequently Asked Questions ==

= Why bother? =

One of the API providers I use had decided to block requests containing 'WordPress' in their useragent string, this forced me to change the useragent to be able to communicate with that particular API.

= How do I restore the default useragent? =

Leave the useragent field blank when saving the settings. If no useragent string is configured the default useragent will be used.

== Screenshots ==


== Changelog ==

= 1.0 =
* Initial release.
