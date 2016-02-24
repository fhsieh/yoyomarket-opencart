yoyomarket-opencart
============

Ecommerce system for Yoyo Market built on OpenCart and based on Stowear theme.

![Desktop and mobile design](https://raw.githubusercontent.com/fhsieh/yoyomarket-opencart/master/screenshots/design.png)

Features: full multi-language localization, full responsive design, quick searching, @2x images, localization for shipping within Japan and scripts to support custom order request with Kuroneko Yamato shipping policies.

---

### Localization

**Quick Checkout**
`catalog/view/javascript/yoyo-quick-checkout.js`

Customers must be allowed to choose a specific delivery date and time for their order. The available delivery dates and times depend on the date and time of order and shipping address, according to the following rules:

1. Orders placed Monday-Thursday before 12pm will ship the same day.
2. Orders placed Friday before 1pm will ship the same day.
3. Orders placed after Monday-Thursday 12pm, after Friday 1pm, or on Saturday-Sunday, will ship the following weekday.
4. Earliest available delivery date for most parts of Japan should be 1 day after ship day, and available delivery times are 8-12, 12-14, 14-16, 16-18, 18-20, and 20-21.
5. Earliest available delivery date for Hokkaido, Kyushu, and Okinawa should be 2 days after the ship day.
6. Earliest available delivery time for next-day delivery for Akita, Aomori, Ehime, Hiroshima, Kagawa, Kochi, Okayama, Shimane, Tokushima, Tottori, Wakayama, and Yamaguchi prefectures should be 14-16 (ie: 8-12 and 12-14 should not be available when next-day delivery is selected).

During checkout, OpenCart passes the customer's selected shipping address and the current server date/time to `yoyo-quick-checkout.js`. The script calculates the earliest available delivery date and time based on the arguments, and uses jQuery to modify the datepicker component and time select dropdown component to reflect the delivery date/time options available for the customer.

![Shipping to Tokyo, Okinawa, and Yamaguchi](https://raw.githubusercontent.com/fhsieh/yoyomarket-opencart/master/screenshots/shipping.png)



**Prefecture Sorting**

List of prefectures in Japan are sorted according to different localization standards. In English, prefectures are generally sorted A-to-Z, whereas in Japan, prefectures are sorted in their relative North-to-South geographic location in Japan (rather than UTF sorting) according to the [JIS X 0401 standard](https://ja.wikipedia.org/wiki/%E5%85%A8%E5%9B%BD%E5%9C%B0%E6%96%B9%E5%85%AC%E5%85%B1%E5%9B%A3%E4%BD%93%E3%82%B3%E3%83%BC%E3%83%89#.E9.83.BD.E9.81.93.E5.BA.9C.E7.9C.8C.E3.82.B3.E3.83.BC.E3.83.89). i18n for prefectures is provided by `mods/krotek_add_multilingual_zones.ocmod.xml` which adds inline i18n without modifying the database structure and adds methods to parse the correct i18n string, and custom sorting is implemented in `mods/yym_add_jp_zone_sorting.ocmod.xml` which detects the customer's current language in the session variable and applies either an alphabetical or code sort method.

![Sorting in English vs Japanese](https://raw.githubusercontent.com/fhsieh/yoyomarket-opencart/master/screenshots/sorting.png)



---

### Other Modifications

**Custom Invoice Templates**
`yym_add_custom_invoice_templates.ocmod.xml`

Add customized printable views for 1) customer invoice/receipt, 2) product purchase list, and 3) packing list.

![Printable views](https://raw.githubusercontent.com/fhsieh/yoyomarket-opencart/master/screenshots/prints.png)


**Modify Admin Order View**
`yym_modify_admin_order.ocmod.xml`

Modify default OpenCart order detail view for consolidated view, inline AJAX editing billing/shipping/product editing, etc.

![Custom order view](https://raw.githubusercontent.com/fhsieh/yoyomarket-opencart/master/screenshots/order.jpg)



**Modify Admin Product Management View**
`yym_modify_admin_product.ocmod.xml`

Extend **Admin Quick Edit** extension to add additional editable fields.

![Custom product management view](https://raw.githubusercontent.com/fhsieh/yoyomarket-opencart/master/screenshots/products.jpg)



Additional modifications can be found in `mods` (ocmod type) or `vqmod/xml` (vqmod type).

---

### Provisioning

Runs on most xAMP servers running **PHP >5.3** with `mod_rewrite` enabled for readable URLs (`.htaccess` included), and **MySQL >5.6**.

Also tested on **nginx**. For readable URLs, include the following rewrite rules in the server conf section:

    server {
		...
	
		rewrite ^/sitemap.xml$ /index.php?route=feed/google_sitemap last;
		rewrite ^/googlebase.xml$ /index.php?route=feed/google_base last;
		rewrite ^/download/(.*) /index.php?route=error/not_found last;
		if (!-f $request_filename) {
			set $rule_3 1$rule_3;
		}
		if (!-d $request_filename) {
			set $rule_3 2$rule_3;
		}
		if ($uri !~ ".*.(ico|gif|jpg|jpeg|png|js|css)") {
			set $rule_3 3$rule_3;
		}
		if ($rule_3 = "321") {
			rewrite ^/([^?]*) /index.php?_route_=$1 last;
		}	

		...
	}

---

### Testing

Testing requires db seed and is not provided in this repository for security reasons. A live version of the system may be accessed at ~~yoyomarket.jp~~ [hilomarket.com](http://hilomarket.com) (rebranded in February 2016).
