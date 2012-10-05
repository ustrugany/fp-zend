<?php

class My_Slugs
{

    /*
     *
     *  $options = array(
     *      'encoding'  => 'utf-8',
     *      'default'   => 'undefined',
     *      'separator' => '-',
     *      'maxlength' => 100,
     *      'case'      => 'lower'
     *   );
     */
    public static function string2slug($string = '', $options = array())
    {

        if (!isset($options['separator'])) {
            $options['separator'] = '-';
        }

        if (!isset($options['default'])) {
            $options['default'] = 'undefined';
        }

        if (!isset($options['encoding'])) {
            $options['encoding'] = 'utf-8';
        }

        if (!isset($options['case'])) {
            $options['case'] = 'lower';
        }

        switch ($options['encoding']) {
        case 'utf-8':
            $string = My_Pl::utf82ascii($string);
            break;
        case 'iso-8859-2':
            $string = My_Pl::iso2ascii($string);
            break;

        case 'windows-1250':
            $string = My_Pl::win2ascii($string);
            break;
        }

        $string = preg_replace('/[^A-Za-z0-9]/', $options['separator'], $string);

        if (isset($options['case'])) {
            if ($options['case'] == 'lower') {
                $string = strtolower($string);
            } else if ($options['case'] == 'upper') {
                $string = strtoupper($string);
            }
        }

        $string = preg_replace('/' . preg_quote($options['separator'], '/') . '{2,}/', $options['separator'], $string);
        $string = trim($string, $options['separator']);

        if (isset($options['maxlength'])) {
            $string = self::abbr($string, $options);
        }

        if ($string === '') {
            return $options['default'];
        } else {
            return $string;
        }
    }

    public static function html2slug($string, $options = array())
    {
        if (!isset($options['encoding'])) {
            $options['encoding'] = 'utf-8';
        }
        $string = strip_tags($string);
        $string = html_entity_decode($string, ENT_QUOTES, $options['encoding']);
        $string = self::string2slug($string, $options);
        return $string;
    }


    public static function abbr($string, $options = array())
    {
        if (isset($options['maxlength'])) {
            $maxlength = $options['maxlength'];
        } else {
            $maxlength = 100;
        }

        if (strlen($string) < $maxlength) {
            return $string;
        }

        if (isset($options['separator'])) {
            $separator = $options['separator'];
        } else {
            $separator = '-';
        }

        $i = $maxlength;

        do {
            $i--;
        } while (($i >= 0) && ($string[$i] != $separator));

        return substr($string, 0, $i);
    }


}