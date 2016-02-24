var debug = false,
    preamble = 'yoyo-quick-checkout.js: ',
    rule = '';

/**
 * jQuery selectors
 */
var dp = '.date',
    tp = 'select[name="delivery_time"]',
    dtp = 'DateTimePicker';

/**
 * Localization strings
 */
var strings = {
  noPref: {
    en: 'No preference',
    ja: '指定なし'
  },
  dateFormat: {
    en: 'dddd, MMM DD',
    ja: 'MM月 DD日',
    int: 'YYYY-MM-DD'
  },
  notAvailable: {
    en: '(not available)',
    ja: '(指定できない)'
  }
};

/**
 * Zone definitions
 */
var zones = {
  // next day delivery
  1657: 1, // Aichi
  1660: 1, // Chiba
  1662: 1, // Fukui
  1664: 1, // Fukushima
  1665: 1, // Gifu
  1666: 1, // Gunma
  1669: 1, // Hyogo
  1670: 1, // Ibaraki
  1671: 1, // Ishikawa
  1672: 1, // Iwate
  1675: 1, // Kanagawa
  1678: 1, // Kyoto
  1679: 1, // Mie
  1680: 1, // Miyagi
  1682: 1, // Nagano
  1684: 1, // Nara
  1685: 1, // Niigata
  1689: 1, // Osaka
  1691: 1, // Saitama
  1692: 1, // Shiga
  1694: 1, // Shizuoka
  1695: 1, // Tochigi
  1697: 1, // Tokyo
  1699: 1, // Toyama
  1701: 1, // Yamagata
  1703: 1, // Yamanashi
  // next day delivery after 14:00
  1658: 2, // Akita
  1659: 2, // Aomori
  1661: 2, // Ehime
  1667: 2, // Hiroshima
  1673: 2, // Kagawa
  1676: 2, // Kochi
  1687: 2, // Okayama
  1693: 2, // Shimane
  1696: 2, // Tokushima
  1698: 2, // Tottori
  1700: 2, // Wakayama
  1702: 2, // Yamaguchi
  // two day delivery
  1663: 3, // Fukuoka
  1668: 3, // Hokkaido
  1674: 3, // Kagoshima
  1677: 3, // Kumamoto
  1681: 3, // Miyazaki
  1683: 3, // Nagasaki
  1686: 3, // Oita
  1688: 3, // Okinawa
  1690: 3  // Saga
};

/**
 * Rules for calculating first delivery date
 */
var offsets = {
  zone: function(zone) {
    if (zone == 1) {
      rule += 'next day delivery, ';
      return 0;
    } else if (zone == 2) {
      rule += 'next day delivery after 14:00, ';
      return 0;
    } else if (zone == 3) {
      rule += 'two day delivery, ';
      return 1;
    }
  },
  cutoff: function(day, hour) {
    if (day >= 1 && day <= 4) {
      rule += 'mon-thu ';
      if (hour >= 12) {
        rule += 'after cutoff, ships tomorrow';
        return 1;
      }
    } else if (day == 5) {
      rule += 'fri ';
      if (hour >= 13) {
        rule += 'after cutoff, ships monday';
        return 3;
      }
    } else if (day == 6) {
      rule += 'sat, ships monday';
      return 2;
    } else if (day == 0) {
      rule += 'sun, ships monday';
      return 1;
    }
    rule += 'before cutoff, ships today';
    return 0;
  }
};

function initShipping(zone, locale, datetime) {
  if (debug) console.log(preamble + "loaded successfully");
  if (debug) console.log(preamble + "passed zone from controller: " + zone);
  if (debug) console.log(preamble + "passed locale from controller: " + locale);
  if (debug) console.log(preamble + "passed datetime from controller: " + datetime);

  var now = moment(datetime),
      today = now.format('d');

  /**
   * Check data passed from controller
   */
  if ($.inArray(locale, ['en', 'ja']) == -1) {
    locale = 'en'; // default to english
  }
  if (typeof zones[zone] == 'undefined') {
    zone = 1660; // default to store location
  }

  /**
   * Add "No preference" option to date picker
   */
  $('.datepicker').each(function() {
    if ($(this).next('.datepicker-clear').length == 0) {
      if (debug) console.log(preamble + "no preference option added to date picker");
      $(this).after(
        $('<div>' + strings.noPref[locale] + '</div>')
          .addClass('datepicker-clear')
          .click(function() {
            $(dp).data(dtp).setDate();
            $(dp).val(strings.noPref[locale]);
          })
      );
    }
  });

  /**
   * Parse delivery zone
   */
  if (zone != '') {
    // Get zone passed from checkout controller
    zone = zone;
  } else if ($('#payment-address #shipping').is(':checked')) {
    // Guest, payment = shipping, get zone from selected payment address zone
    zone = $('#input-payment-zone option:selected').text();
  } else if ($('#shipping-address-existing').is(':checked')) {
    // Logged in, get zone from existing shipping address zone
    zone = $('#shipping-existing option:selected').text();
    zone = zone.substr(0, zone.lastIndexOf(', ')); // Remove ', Japan' and ', 日本'
    zone = zone.substr(zone.lastIndexOf(', ') + 2); // Isolate prefecture
  } else {
    // Guest and payment != shipping or Logged in and new address, get zone from shipping address zone
    zone = $('#input-shipping-zone option:selected').text();
  }
  rule += zone + ', ';

  /**
   * Calculate offset rules and set earliest delivery date
   */
  var offset = 1 + offsets.zone(zones[zone]) + offsets.cutoff(parseInt(today), parseInt(now.hour()));
  var minDate = now.add(offset, 'd');

  if (debug) console.log(preamble + rule);
  if (debug) console.log(preamble + "earliest delivery date is " + minDate.format('dddd, MMMM DD'));

  /**
   * Disable 08:00-12:00 and 12:00-14:00 for zone 2
   */
  if (zones[zone] == 2) {
    if (debug) console.log(preamble + "setting dp.change handler for zone 2");
    $(dp).on('dp.change', function() {
      if ($(dp).data(dtp).getDate() != null && $(dp).data(dtp).getDate().format(strings.dateFormat['int']) === minDate.format(strings.dateFormat['int'])) {
        if (debug) console.log(preamble + "user selected earliest day, disabling 08:00-12:00 and 12:00-14:00");
        if ($('option:nth-child(2)', tp).is(':selected') || $('option:nth-child(3)', tp).is(':selected')) {
          $(tp).val($('option:first-child', tp).val());
        }
        $('option:nth-child(2), option:nth-child(3)', tp).each(function() {
          $(this)
            .attr('disabled', 'disabled')
            .addClass('strike')
            .text($(this).val() + ' ' + strings.notAvailable[locale]);
        });
      } else {
        $('option[disabled="disabled"]', tp).each(function() {
          $(this)
            .removeAttr('disabled')
            .removeClass('strike')
            .text($(this).val());
        });
      }
    });
  }

  /**
   * Set date picker range, format, disable autofill, and clear any existing date
   */
  $(dp).data(dtp).format = strings.dateFormat[locale];
  $(dp).data(dtp).setMinDate(minDate.format(strings.dateFormat[locale]));
  $(dp).data(dtp).options.useCurrent = false;
  $(dp).data(dtp).setDate();
  $(dp).removeAttr('value');
  $(dp).attr('placeholder', strings.noPref[locale]);
  $(dp).val(strings.noPref[locale]);

}