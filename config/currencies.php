<?php

/*
 * Supported currencies with approximate exchange rates against USD.
 * Rates are indicative — replace with a live-rate feed by updating CurrencyService.
 */
return [
    'base' => 'USD',

    'supported' => [
        'USD' => ['name' => 'US Dollar',          'symbol' => '$',    'rate' => 1.0],
        'EUR' => ['name' => 'Euro',                'symbol' => '€',    'rate' => 0.92],
        'GBP' => ['name' => 'British Pound',       'symbol' => '£',    'rate' => 0.79],
        'CAD' => ['name' => 'Canadian Dollar',     'symbol' => 'CA$',  'rate' => 1.36],
        'AUD' => ['name' => 'Australian Dollar',   'symbol' => 'A$',   'rate' => 1.52],
        'SAR' => ['name' => 'Saudi Riyal',         'symbol' => '﷼',    'rate' => 3.75],
        'AED' => ['name' => 'UAE Dirham',          'symbol' => 'د.إ',  'rate' => 3.67],
        'MAD' => ['name' => 'Moroccan Dirham',     'symbol' => 'د.م',  'rate' => 9.90],
        'DZD' => ['name' => 'Algerian Dinar',      'symbol' => 'د.ج',  'rate' => 134.5],
        'TND' => ['name' => 'Tunisian Dinar',      'symbol' => 'د.ت',  'rate' => 3.12],
        'MRU' => ['name' => 'Mauritanian Ouguiya', 'symbol' => 'أ.م',  'rate' => 39.6],
    ],
];
