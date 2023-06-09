=== Event Tickets Extension: Test Data Generator ===
Contributors: theeventscalendar, aguseo, bordoni, Camwyn, tecjoel, lucatume
Donate link: http://evnt.is/29
Tags: event, tickets
Requires at least: 5.9
Tested up to: 6.2
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPL version 3 or any later version
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This extension aims to provide an automated tool to generate high quality, life-like data for the Event Tickets suite of plugins.

== Installation ==

Install and activate like any other plugin!

* You can upload the plugin zip file via the *Plugins ‣ Add New* screen
* You can unzip the plugin and then upload to your plugin directory (typically _wp-content/plugins_) via FTP
* Once it has been installed or uploaded, simply visit the main plugin list and activate it

== Frequently Asked Questions ==

= Where can I find more extensions? =

Please visit our [extension library](https://theeventscalendar.com/extensions/) to learn about our complete range of extensions for The Events Calendar and its associated plugins.

= What if I experience problems? =

Please create a GitHub issue inside the project.

== Changelog ==

= [1.1.0] 2023-06-22 =

* Version - Event Tickets Test Data Generator 1.1.0 is only compatible with The Events Calendar 6.1.2 and higher.
* Version - Event Tickets Test Data Generator 1.1.0 is only compatible with Event Tickets 5.6.1 and higher.
* Fix - Lock our container usage(s) to the new Service_Provider contract in tribe-common. This prevents conflicts and potential fatals with other plugins that use a di52 container.

= [1.0.0] 2023-04-11 =

* Feature - Automatically generate test RSVPs for an Event.
* Feature - Automatically generate test Tickets for an Event.
* Feature - Automatically generate test Attendees for a Ticket or RSVP.
* Feature - WP-CLI support for the generator functionality.
