=== CSV Import ===
Contributors: smackcoders
Donate link: https://www.paypal.me/smackcoders
Tags: import posts, import pages, csv importer, import csv
Requires at least: 5.2
Tested up to: 4.6.1
Stable tag: 2.0
Version: 2.0
Author: smackcoders
Author URI: http://profiles.WordPress.org/smackcoders/
License: GPLv2 or later

We have decided to discountinue this plugin, please migrate to https://wordpress.org/plugins/wp-ultimate-csv-importer/

== Description ==

Imp. Note: The plugin will be removed soon from wordpress plugin directory, please migrate to https://wordpress.org/plugins/wp-ultimate-csv-importer

CSV Import enables to import Post, Page and all Custom Posts along with default Custom Fields and SEO fields. WooCommerce Products, Variations, Orders, Coupons, Refunds can also be imported.

Feature images can be imported along with the Post, Page, Products and all Custom Posts

Please show your support for CSV Import by giving it a
[rating](https://wordpress.org/support/plugin/csv-import/reviews/?filter=5#new-post) :)

= Highlights =

* Imports Tags and Categories.
* Imports default WordPress fields and other custom fields.
* Supports all Custom Posts created in WordPress standard.
* Real-time logs for the imported data with website view option.
* Supports All in One SEO plugin & Yoast SEO plugin for SEO fields import.

= Procedure =

* Upload the CSV to be imported.
* Click on 'Upload file and import' to proceed import.
* Choose post type which you need to import.
* Click on 'Import Now' to start the import.


[youtube https://www.youtube.com/watch?v=S1P8KebpLaU&list=PL2k3Ck1bFtbTtSX3hEFoBvK_Ka0pfYwp_]

= Ultimate CSV Importer PRO =
Ultimate CSV Importer PRO has all the features of CSV Import and it is extended with a lot of features that enables to [import](https://www.youtube.com/watch?v=S1P8KebpLaU&index=1&list=PL2k3Ck1bFtbTtSX3hEFoBvK_Ka0pfYwp_), schedule, [update](https://www.youtube.com/watch?v=VEumAqpJfSw&list=PL2k3Ck1bFtbTtSX3hEFoBvK_Ka0pfYwp_&index=2) and [export](https://www.youtube.com/watch?v=0YA0au4cXW0) both CSV and XML files. The import details over a year is mentioned in dashboard chart and the site updates can be tracked in a fraction of a second. Any changes like modifying existing content or appending new content can be done with the update. The update or import can be performed periodically with scheduler. The update or schedule is done with the templates which is created with mapped fields in the mapping section of the import. The export option enables to export the imported modules as CSV based on the filters.
= Supported Modules To Import and Export =
* Core Module - Post, Page, Custom Post, Category, Tags, Users, Customer Reviews.
* eCommerce Products - WooCommerce, MarketPress, WP e-Commerce & eShop.
* SEO field - All in One SEO and Yoast SEO.
* Custom fields - CustomPress, ACF, Types, Pods, CCTM & WordPress default fields.
* Custom Post - CustomPress, Types, Pods, CPT UI, CCTM.
= Highlighted Feature of PRO =
* [Import inline image with shortcode and featured images with shortcode or external URL.](https://www.youtube.com/watch?v=VvcVLERTEHo)
* The image zip can be uploaded even after the completion of the import flow to populate the shortcode image.
* Supports WooCommerce product import along with 5 WooCommerce add-on.
* Imports MarketPress PRO add-on to import products.
* Registers Custom Fields of [ACF](https://www.youtube.com/watch?v=6tdiOjw4EDk&list=PL2k3Ck1bFtbTtSX3hEFoBvK_Ka0pfYwp_&index=4), Types, [Pods](https://www.youtube.com/watch?v=ziDeb0iXXXA&list=PL2k3Ck1bFtbTtSX3hEFoBvK_Ka0pfYwp_&index=5) and WordPress fields in the flow of import.
* Supports Custom Posts created with CPT UI, CCTM, Types and Pods
* Specific record import. (i.e) Selective records in a CSV/XML can be imported or scheduled.
* [Imported modules can be managed in the File manager and the logs are maintained in the Log manager.](https://www.youtube.com/watch?v=iBqWrq7M6g0)

= Helpful Links =
[Documentation](https://www.smackcoders.com/documentation/ultimate-csv-importer-pro/how-to-import-csv).

You can try the [live demo](http://demo.smackcoders.com/wp-ultimate-csv-importer/wp-admin) or request for free trial by clicking Try Now in [WordPress Ultimate CSV Importer](https://www.smackcoders.com/wp-ultimate-csv-importer-pro.html) page to generate your own WordPress test environment.

Read our [blog](https://www.smackcoders.com/blog/category/wordpress-products) to know more about the new [features](https://www.smackcoders.com/wp-ultimate-csv-importer-pro/features.html) of our WordPress plugins.

[FAQ](https://www.smackcoders.com/wordpress-products-faq.html) and Troubleshooting tips](https://wordpress.org/support/topic/tips-to-trouble-shoot-wp-ultimate-csv-importer-issues-1)

== Installation ==

1. Extract the csv-import.zip in wordpress/wp-content/plugins using FTP or through plugin install in wp-admin.
2. Activate the plugin in the WordPress plugin list.

== Screenshots ==

1. CSV Upload section.
2. Sample WordPress Fields and the CSV Headers list
3. Choose the Post type from dropdown.
4. Log for the import data.

== Frequently Asked Questions ==

1. Is there any limitation on file size?<br />
The size of the file is based on the Upload size of your server configuration. The upload_max_filesize should be at least 2M. You can verify your system values in settings -> security and performance.

2. Can we import any number of Custom Fields ?<br />
Yes, without any limitations any number of fields can be imported.

3. Can we include an external URL for image import?<br />
Yes, Featured image can be imported from external URL.<br />

4. How to prepare a CSV file?<br />
The column headers in CSV must be same as your WordPress field names. You can refer the screenshot-2 to know what the WordPress field name is.<br />
Ex:- <br />
1) Default headers should be like post_title, post_content, post_date, post_format, post_status, etc., <br />
2) Tag column need to be mentioned as post_tag in CSV.<br />
3) Category column need to be mentioned as post_category in CSV.<br />
4) If you have a custom field with WordPress field name "date_of_joining"(Field name is not the field label), then your CSV file should “date_of_joining” header with the list of dates in the PHP standard.<br />

5. How to enable the import button?<br />
The import button will be enabled after uploading the CSV file to be imported. If not enabled, verify whether the file is properly formatted.<br />

6. Can we import into Custom Post Type created with any third party plugin?<br />
Yes, You can import any custom post types created in WordPress standard.<br />

== Changelog ==

= 2.0 =
* Modified: CSVParserEngine with SmackCSVParser, a high-speed robust parser.
* Improved: No restrictions, You can import any post types which is created with WordPress standards.
* Added: Compatibility for WordPress 4.6.1.

= 1.2 =
* Added: Compatibility for WordPress 4.5.3.
* Added: Multi category import with comma(,) and pipeline(|) separation.

= 1.1 =
* Added: Compatibility for WordPress 4.4.2.
* Fixed: Vulnerability issue was fixed.

= 1.0 =
* Initial release version. Tested and found to work well without any issues.

== Upgrade Notice ==

= 2.0 =
* Upgrade now to get high speed robust parser and WordPress 4.6.1 compatibility.

= 1.2 =
* Upgrade now for multi category import with comma and WordPress 4.5.3 compatibility.

= 1.1 =
* Upgrade now for security fix and WordPress 4.4.2 compatibility.

= 1.0 =
* Initial release of plugin.
