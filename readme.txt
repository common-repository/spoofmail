=== SpoofMail ===
Contributors: Fubra, ray.viljoen
Tags: fake email, email, spoof, email validate, email check, temporary email, mx-record
Requires at least: 3.0
Tested up to: 3.4.2
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=99WUGVV4HY5ZE&lc=GB&item_name=CATN%20Plugins-CDN&item_number=catn-cdn&currency_code=GBP&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Stable tag: trunk

Validate an email address' structure, check MX-Records and check against known spoof/temporary email domains.

== Description ==

SpoofMail validates an email by checking the email's structure, finding the email's MX-Records and checking that the email has not been generated from a temporary or fake email provider.

SpoofMail provides an asyncronous javascript function for validating emails and can also be used as a jQuery plugin if jQuery is available. (jQuery is not required)
Functions exist for both PHP & JavaScript.

The functions provided:

= PHP =

The SpoofMail PHP function takes an email and an optional callback function.
The function also returns a simple Boolean for 'pass' or 'fail'.
The callback contains one argument with an array of failed checks or NULL if the email passed all checks.

sm_verify_email( $email, $callback )

= JavaScript =

The SpoofMail JavaScript function takes an email and a callback function.
The callback contains two arguments. The first is a simple object containing the 'pass' or 'fail' status and an array of failed checks.
The second argument contains the entire response object and can mostly be ignored.

spoofMail(email, callback)

The JavaScript function is also available as a jQuery function if jQuery is included.

$.spoofMail(email, callback)

Developed by <a href="http://www.catn.com">PHP Hosting Experts CatN</a>

== Installation ==

1. Upload the `spoofMail` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

or use the automatic installation method:

1. Log in to your blog and go to the Plugins page.
2. Click Add New button.
3. Search for Benchmark Email Lite.
4. Click Install Now link.
5. (sometimes required) Enter your FTP or FTPS username and password, as provided by your web host.
6. Click Activate Plugin link.

== Changelog ==

= 1.0 =
* First Release.

== Upgrade Notice ==