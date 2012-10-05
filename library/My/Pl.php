<?php

class My_Pl
{

    /*
     *   ASCII
     *
     */

    const ASCII_ALL             = 'acelnoszzACELNOSZZ';
    const ASCII_UPPER           = 'ACELNOSZZ';
    const ASCII_LOWER           = 'acelnoszz';
    const ASCII_SPECIFIC        = 'aszASZ';
    const ASCII_SPECIFIC_UPPER  = 'ASZ';
    const ASCII_SPECIFIC_LOWER  = 'asz';
    const ASCII_COMMON          = 'celnozCELNOZ';
    const ASCII_COMMON_UPPER    = 'CELNOZ';
    const ASCII_COMMON_LOWER    = 'celnoz';

    public static $_ARRAY_ASCII = array(
        'a', 'c', 'e', 'l', 'n', 's', 'z', 'z',
        'A', 'C', 'E', 'L', 'N', 'S', 'Z', 'Z'
    );




    /*
     *   ISO-8859-2
     *
     */

    //ąćęłńóśźżĄĆĘŁŃÓŚŹŻ
    const ISO_ALL = "\xb1\xe6\xea\xb3\xf1\xf3\xb6\xbc\xbf\xa1\xc6\xca\xa3\xd1\xd3\xa6\xac\xaf";

    public static $_ARRAY_ISO_ALL = array(
        "\xb1", "\xe6", "\xea", "\xb3", "\xf1", "\xf3", "\xb6", "\xbc", "\xbf",
        "\xa1", "\xc6", "\xca", "\xa3", "\xd1", "\xd3", "\xa6", "\xac", "\xaf"
    );



    //ĄĆĘŁŃÓŚŹŻ
    const ISO_ALL_UPPER = "\xa1\xc6\xca\xa3\xd1\xd3\xa6\xac\xaf";

    public static $_ARRAY_ISO_ALL_UPPER = array(
        "\xa1", "\xc6", "\xca", "\xa3", "\xd1", "\xd3", "\xa6", "\xac", "\xaf"
    );



    //ąćęłńóśźż
    const ISO_ALL_LOWER = "\xb1\xe6\xea\xb3\xf1\xf3\xb6\xbc\xbf";

    public static $_ARRAY_ISO_ALL_LOWER = array(
        "\xb1", "\xe6", "\xea", "\xb3", "\xf1", "\xf3", "\xb6", "\xbc", "\xbf"
    );


    //ąśźĄŚŹ
    const ISO_SPECIFIC = "\xb1\xb6\xbc\xa1\xa6\xac";

    public static $_ARRAY_ISO_SPECIFIC = array(
        "\xb1", "\xb6", "\xbc",
        "\xa1", "\xa6", "\xac"
    );


    //ĄŚŹ
    const ISO_SPECIFIC_UPPER = "\xa1\xa6\xac";

    public static $_ARRAY_ISO_SPECIFIC_UPPER = array(
        "\xa1", "\xa6", "\xac"
    );


    //ąśź
    const ISO_SPECIFIC_LOWER = "\xb1\xb6\xbc";

    public static $_ARRAY_ISO_SPECIFIC_LOWER = array(
        "\xb1", "\xb6", "\xbc"
    );




    /*
     *   WINDOWS-1250
     *
     */

    //ąćęłńóśźżĄĆĘŁŃÓŚŹŻ
    const WIN_ALL = "\xb9\xe6\xea\xb3\xf1\xf3\x9c\x9f\xbf\xa5\xc6\xca\xa3\xd1\xd3\x8c\x8f\xaf";

    public static $_ARRAY_WIN_ALL = array(
        "\xb9", "\xe6", "\xea", "\xb3", "\xf1", "\xf3", "\x9c", "\x9f", "\xbf",
        "\xa5", "\xc6", "\xca", "\xa3", "\xd1", "\xd3", "\x8c", "\x8f", "\xaf"
    );


    //ĄĆĘŁŃÓŚŹŻ
    const WIN_ALL_UPPER = "\xa5\xc6\xca\xa3\xd1\xd3\x8c\x8f\xaf";

    public static $_ARRAY_WIN_ALL_UPPER = array(
        "\xa5", "\xc6", "\xca", "\xa3", "\xd1", "\xd3", "\x8c", "\x8f", "\xaf"
    );


    //ąćęłńóśźż
    const WIN_ALL_LOWER = "\xb9\xe6\xea\xb3\xf1\xf3\x9c\x9f\xbf";

    public static $_ARRAY_WIN_ALL_LOWER = array(
        "\xb9", "\xe6", "\xea", "\xb3", "\xf1", "\xf3", "\x9c", "\x9f", "\xbf"
    );


    //ąśźĄŚŹ
    const WIN_SPECIFIC = "\xb9\x9c\x9f\xa5\x8c\x8f";

    public static $_ARRAY_WIN_SPECIFIC = array(
        "\xb9", "\x9c", "\x9f",
        "\xa5", "\x8c", "\x8f"
    );


    //ĄŚŹ
    const WIN_SPECIFIC_UPPER = "\xa5\x8c\x8f";

    public static $_ARRAY_WIN_SPECIFIC_UPPER = array(
        "\xa5", "\x8c", "\x8f"
    );


    //ąśź
    const WIN_SPECIFIC_LOWER = "\xb9\x9c\x9f";

    public static $_ARRAY_WIN_SPECIFIC_LOWER = array(
        "\xb9", "\x9c", "\x9f"
    );


    //ćęłńóżĆĘŁŃÓŻ
    const COMMON = "\xe6\xea\xb3\xf1\xf3\xbf\xc6\xca\xa3\xd1\xd3\xaf";

    public static $_ARRAY_COMMON = array(
        "\xe6", "\xea", "\xb3", "\xf1", "\xf3", "\xbf",
        "\xc6", "\xca", "\xa3", "\xd1", "\xd3", "\xaf"
    );


    //ĆĘŁŃÓŻ
    const COMMON_UPPER = "\xc6\xca\xa3\xd1\xd3\xaf";

    public static $_ARRAY_COMMON_UPPER = array(
        "\xc6", "\xca", "\xa3",
        "\xd1", "\xd3", "\xaf"
    );


    //ćęłńóż
    const COMMON_LOWER = "\xe6\xea\xb3\xf1\xf3\xbf";

    public static $_ARRAY_COMMON_LOWER = array(
        "\xe6", "\xea", "\xb3",
        "\xf1", "\xf3", "\xbf"
    );




    /*
     *   UTF-8
     *
     */

    //ąćęłńóśźżĄĆĘŁŃÓŚŹŻ
    public static $_ARRAY_UTF8 = array(
        "\xc4\x85", "\xc4\x87", "\xc4\x99", "\xc5\x82", "\xc5\x84", "\xc3\xb3", "\xc5\x9b", "\xc5\xba", "\xc5\xbc", "\xc4\x84", "\xc4\x86", "\xc4\x98", "\xc5\x81", "\xc5\x83", "\xc3\x93", "\xc5\x9a", "\xc5\xb9", "\xc5\xbb",
    );



    /*
     *   TRANSLITERACJA JEDNOZNAKOWA
     *
     *   Tablica $_ARRAY_TRANSLITERATE wymusza kodowanie pliku utf-8
     */
    public static $_ARRAY_TRANSLITERATE = array(
            'é' => 'e', 'ö' => 'o', 'ş' => 's', 'ü' => 'u',
            'á' => 'a', 'ñ' => 'n', 'ç' => 'c', 'è' => 'e',
            'ß' => 'ss'
        );


    /*
     *   Source: ISO-8859-2
     *
     */

    public static function iso2win($string)
    {
        return strtr($string, self::ISO_SPECIFIC, self::WIN_SPECIFIC);
    }

    public static function iso2utf8($string)
    {
        return iconv('ISO-8859-2', 'UTF-8', $string);
    }

    public static function iso2utf16($string)
    {
        return iconv('ISO-8859-2', 'UTF-16', $string);
    }

    public static function iso2ascii($string)
    {
        return strtr($string, self::ISO_ALL, self::ASCII_ALL);
    }





    /*
     *   Source: WINDOWS-1250
     *
     */

    public static function win2iso($string)
    {
        return strtr($string, self::WIN_SPECIFIC, self::ISO_SPECIFIC);
    }

    public static function win2utf8($string)
    {
        return iconv('WINDOWS-1250', 'UTF-8', $string);
    }

    public static function win2utf16($string)
    {
        return iconv('WINDOWS-1250', 'UTF-16', $string);
    }

    public static function win2ascii($string)
    {
        return strtr($string, self::WIN_ALL, self::ASCII_ALL);
    }




    /*
     *   Source: UTF-8
     *
     */

    public static function utf82iso($string)
    {
        return iconv('UTF-8', 'ISO-8859-2', $string);
    }


    public static function utf82win($string)
    {
        return iconv('UTF-8', 'WINDOWS-1250', $string);
    }

    public static function utf82utf16($string)
    {
        return iconv('UTF-8', 'UTF-8', $string);
    }

    public static function utf82ascii($string)
    {
        $string = self::transliterate($string);

        /*
         * Urywamy wszystkie ogonki różne od polskich
         * Polskie ogonki kodujemy w iso
         */
        $string = iconv('utf-8', 'ISO-8859-2//TRANSLIT//IGNORE', $string);

        /*
         * urywamy polskie ogonki
         */
        $string = self::iso2ascii($string);

        return $string;

    }


    /*
     * Transliteracja jednoznakowa
     *
     * Urywamy ogonki inne od polskich
     * Chcemy otrzymać kody jednoznakowe
     * Domyślna transliteracja iconv produkuje kody wieloznakowe
     * Na przykład    é => 'e    è => `e
     *
     * Opis transliteracji:
     *     http://oldforum.symfony-project.org/index.php/t/22187/
     *     http://www.symfony-project.org/jobeet/1_2/Propel/en/08
     *     http://php.vrana.cz/vytvoreni-pratelskeho-url.php
     */
    public static function transliterate($string)
    {
        return str_replace(
            array_keys(self::$_ARRAY_TRANSLITERATE),
            array_values(self::$_ARRAY_TRANSLITERATE),
            $string
        );
    }

}