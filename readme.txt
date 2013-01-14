=== RTE Comments ===
Contributors: shazahm1@hotmail.com
Donate link: http://connections-pro.com/
Tags: comment, comments, tinymce, rte
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 1.0.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replaces the default comment form with a rich text editor for logged in admins.

== Description ==

This plugin has one purpose, replace the default comment field that just accepts plain text with a rich text editor similar to the one used when writing posts on admin accounts.

Credit and inspiration for this plugin goes to:
* [Renji](http://sumtips.com/2012/12/add-rich-text-editor-wordpress-comment-form-without-plugin.html)
* [Reuben](http://www.revood.com/blog/adding-visual-editor-to-wordpress-comments-box-part-2/)

== Installation ==

1. Search for RTE Comments on the Plugins : Addnew admin page.
2. Locate RTE Comments in the results.
3. Click Install Now
4. Comfirm the installation.
5. Activate the plugin.
6. That's it. There are no settings to configure.

== Frequently Asked Questions ==

= Why doesn't the rich text editor show up for me? =

One of three reasons or a mixture of any combination...
* You are not logged in as an admin.
* The theme is not using the [comments_form()](http://codex.wordpress.org/Function_Reference/comment_form) template tag.
* There is a javascript error in theme or another plugin.

= Are there any known plugin conflicts? =

Yes, just one with cforms II. The conflict ccan be resolved by changing a couple settings in cforms. On the cforms Global Settings admin page under the WP Editor Button Support section uncheck both options and save the settings.

== Screenshots ==

None.


== Changelog ==

= 1.0.1 01/13/2013 =
* BUG: Fix double slashing of js include.
* OTHER: Include js in the header matching the default position of the comment-reply.js file.
* OTHER: Update readme.txt to include info and resolution about a conflict with cforms II.

= 1.0 01/13/2013 =
* Initial Release.

== Upgrade Notice ==

= 1.0 =
Initial Release.