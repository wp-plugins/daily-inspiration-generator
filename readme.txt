=== Daily Inspiration Generator ===
Contributors: Fire G
Plugin link: http://fire-studios.com
Tags: daily, inspiration, generator, automatic
Requires at least: 2.5.1
Tested up to: 2.9
Stable tag: trunk

Automatically creates a "Daily Inspiration" post at the end of each day.

== Description ==

Automatically generates a daily series of posts showing the most inspiring images from the biggest galleries on the web presented throughout the day.

**Very important that you read the instructions!**

== Change log ==
__1.3.3__
 - Fixed bug that caused multiple instances of cron job

1.3.2
 - Fixed opening slashes bug

1.3.1
 - Fixed auto-publish bug
 - Fixed custom format slashes bug
 - added remover for extra cron scheduals

1.3
 - Added customizations: "tags", "hour", & "limit"

1.2.1
 - Fixed auto-publish selecting bug
 - added warning if not configured correctly

1.2
 - Fixed "fopen()" not working on all servers

1.1.1
 - Added check for returned feed content

1.1
 - Slight changes for stability and compatibility

1.0
 - Automatically posts using cron

Beta 2
 - Writes post
 - Additional customizations: "Title", "Automatically Publish", "Categories", & "Opening"

Beta 1
 - Removes all unwanted data via PHP
 - Initial customizations: "Location" & "Format"

Alpha 1
 - Removes all unwanted data via jQuery
 - Gathers data via Yahoo-Pipe

== Installation ==

1. Download, unzip and upload to your WordPress plugins directory
1. Activate the plugin within the WordPress Administration
1. Go to Settings > Daily Inspiration Generator
1. Adjust Settings as desired
1. Open your `.htaccess` and add `php_flag allow_url_fopen on` to the file
1. That's it. It will post automatically everyday.
