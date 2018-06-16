=== WP Reset - Fastest WordPress Reset Plugin ===
Tags: wordpress reset, reset wordpress, reset database, reset wordpress database, reset, restart wordpress, clean wordpress, default wp, default wordpress, reset wp, wp reset, developer
Contributors: WebFactory
Requires at least: 4.0
Requires PHP: 5.2
Tested up to: 4.9
Stable tag: 1.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress Reset resets any WordPress site to the default values without modifying any files. It deletes all customizations and content. Safe to use.

== Description ==

WP Reset quickly resets the site's database to the default installation values without modifying any files. It deletes all customizations and content. WP Reset is fast and safe to use. It has multiple fail-safe mechanisms so you can never excidentaly lose data. WP Reset is extremely helpful for plugin and theme developers. Access it through the "Tools" menu.


**Please read carefully before proceeding to understand what WP Reset does.**

#### Resetting will delete:

* all posts, pages, custom post types, comments, media entries, users
* all default WP database tables
* all custom database tables that have the same prefix "ins01_" as default tables in this installation

#### Resetting will not delete or modify:

* media files - they'll remain in the _wp-uploads_ folder but will no longer be listed under Media
* no files are touched; plugins, themes, uploads - everything stays
* site title, WordPress address, site address, site language and search engine visibility settings
* currently logged in user will be restored with the current password

#### What happens when I click the Reset button?

* you will have to confirm the action one more time because there is NO UNDO
* everything will be reset; see bullets above for details
* site title, WordPress address, site address, site language, search engine visibility settings as well as the current user will be restored
* you will be logged out, automatically logged in and taken to the admin dashboard
* WP Reset plugin will be reactivated


== Installation ==

Follow the usual routine;

1. Open WordPress admin, go to Plugins, click Add New
2. Enter "wp reset" in search and hit Enter
3. Plugin will show up as the first on the list, click "Install Now"
4. Activate & open plugin's settings page located under the Tools menu

Or if needed, upload manually;

1. Download the plugin.
2. Unzip it and upload to _/wp-content/plugins/_
3. Open WordPress admin - Plugins and click "Activate" next to the plugin
4. Activate & open plugin's settings page located under the Tools menu


== Screenshots ==

1. WP Reset admin page


== Changelog ==

= 1.1 =
* 2018/05/09
* WebFactory took over development
* numerous bug fixes and improvements
* 30,000 installations; 199,000 downloads

= 1.0 =
* 2016/05/16
* Initial release

== Frequently Asked Questions ==

= How can I log in after resetting? =

Use the same username and password you used while doing the reset. Only one user will be restored after resetting. The one you used at that time.

= Will any files be deleted or modified? =

No. All files are left untouched.

= Will I have to reconfigure wp-config.php? =

Absolutely not. No reconfiguration is needed. No files are modified.
