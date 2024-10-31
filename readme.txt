=== Plugin Name ===
Contributors: robertkay
Donate link: http://musicletloose.org
Tags: music, musician, MP3, audio, player, AJAX, HTML5, Flash, record label
Requires at least: 3.4
Tested up to: 4.7.2
Stable tag: 0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Play music while browsing your site!

== Description ==

HTML5 MP3 player with Flash Fallback. Play music while browsing your site - 100% AJAX enabled! Please be gentle with reviews - this software is in public beta & will get more user friendly through your feedback.

Also allows you to organise Artists/Albums/Songs from your wordpress dashboard and display them in your pages/posts via shortcodes and a sidebar widget player.

To see a live demo and instructions go to http://musicletloose.org

This plugin is part of a larger project to provide musicians with free online tools to present and promote their work. We're using http://browserstack.com to check our forthcoming front-end templates on every imaginable device. Many thanks for their great product and their generous support of our open source development program.

== Installation ==

1. unzip package
2. Upload directory `mll-player` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to http://musicletloose.org/instructions for further instructions
(you'll almost certainly need to - wordpress AJAX enabling is not always simple depending on your theme)

== Frequently Asked Questions ==

= When I browse to another page, my music stops when it should keep playing =

You haven't enabled your AJAX in the setting panel (it's disabled by default because it's tricky!)
Read the instructions at http://musicletloose.org/instructions

= How does it work? It's like magic! =

The player is based on the excellent open source http://www.schillmania.com/projects/soundmanager2/ library. Basically it just plays music through that but reloads the content and menu areas of the page via ajax when a link is clicked. Then it uses html5 push.state to ohange the url.

= My page reloads to something really weird, what's happening?! =

If you have javascript content within a post/page then when it loads dynamically it might produce strange results -nothing damaging but can be odd. Luckily people rarely put javascript inside their posts...

== Screenshots ==

== Changelog ==

= 0.7 =
* First release - stable but more features to come...

== Upgrade Notice ==
