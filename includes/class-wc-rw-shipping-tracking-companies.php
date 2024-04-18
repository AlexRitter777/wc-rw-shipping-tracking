<?php

defined('ABSPATH') || exit;

class Wc_Rw_Shipping_Tracking_Companies
{

    protected static $shipping_companies = [

      'DPD' => [
          'tracking_url' => 'https://tracking.dpd.de/status/en_EN/parcel/'
      ],

      'Post services' => [
          'tracking_url' => 'https://www.postaonline.cz/en/trackandtrace/-/zasilka/cislo?parcelNumbers='
      ],

      'Packeta' => [
          'tracking_url' => 'https://tracking.packeta.com/en_GB/?id='
      ]

    ];

    /**
     * @return array
     */
    public static function get_shipping_companies_list(){
        $shipping_companies_list = [];

        foreach (self::$shipping_companies as $company => $properties){
            $shipping_companies_list[] = $company;
        }

        return $shipping_companies_list;
    }

    public static function get_shipping_companies_options_list(){
        $shipping_companies_list = [];
        $shipping_companies_list[''] = 'Select company';

        foreach (self::$shipping_companies as $company => $properties){
            $shipping_companies_list[$company] = $company;
        }

        return $shipping_companies_list;
    }


    /**
     * @param string $shipping_company
     * @return string
     */
    public static function get_shipping_company_tracking_url(string $shipping_company){

        return self::$shipping_companies[$shipping_company]['tracking_url'];

    }

}