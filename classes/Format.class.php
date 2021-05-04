<?php

/**
 * Static formatting class
 */

class Format {
    /* 60 * 60 * 24 * 365.25 */
    const ONE_YEAR = 31557600;
    
    /* 60 * 60 * 24 * 31 */
    const ONE_MONTH = 2678400;
    
    /* 60 * 60 * 24 * 7 */
    const ONE_WEEK = 604800;
    
    /* 60 * 60 * 24 */
    const ONE_DAY = 86400;
    
    /* 60 * 60 */
    const ONE_HOUR = 3600;
    
    /* 60 */
    const ONE_MINUTE = 60;
    
    /**
     * (Uptime) Mute any zeroes. Best to just give an example:
     * muteZeroes(0, 'y') --> ''
     * muteZeroes(1, 'y') --> '1y'
     * etc etc etc.
     * @param int $number - Number to mute.
     * @param string $append - String to append when not muted.
     * @return string
     */
    private static function muteZeroes($number, $append) {
        return ($number > 0) ? ($number . $append) : '';
    }
    
    /**
     * Function to 'disarm' any BBCode formatting in a string.
     */
    static function NoBBC($string) {
        return str_replace(
            array('[', ']', '#', '<', '>',        '_', '*'),
            array('(', ')',  '', '(', ')', "\u{02CD}",  ''),
            $string
        );
    }
    
    /**
     * Function to generate a proper date from a timestamp (with dutch month- and daynames)
     */
    static function DateTime($timestamp) {
        $dagen = array(
            'maandag',
            'dinsdag',
            'woensdag',
            'donderdag',
            'vrijdag',
            'zaterdag',
            'zondag'
        );
        
        $maanden = array(
            'januari',
            'februari',
            'maart',
            'april',
            'mei',
            'juni',
            'juli',
            'augustus',
            'september',
            'oktober',
            'november',
            'december'
        );
        
        return $dagen[date('N', $timestamp) - 1] . date(' j ', $timestamp) . $maanden[date('n', $timestamp) - 1] . date(' Y', $timestamp); 
    }
    
    static function Number($number) {
        return number_format($number, 0, ',', '.');
    }
    
    static function StatNumber($number) {
        switch($number) {
        case 1337:
            return 'leet';
        case 1787569:
            return 'leet^2!!!';
        default:
            return Format::Number($number);
        }
    }
    
    static function Uptime($input) {
        $output = '';
        
        $units = array(
            self::ONE_YEAR => 'y',
            self::ONE_MONTH => 'm',
            self::ONE_WEEK => 'w',
            self::ONE_DAY => 'd',
            self::ONE_HOUR => 'h',
            self::ONE_MINUTE => 'm'
        );
        
        $usedTypes = 0;
        
        foreach ($units as $limit => $append) {
            $muted = self::muteZeroes(floor($input / $limit), $append);
            
            if ($muted) {
                $output .= $muted;
                $input %= $limit;
                
                if (++$usedTypes > 2) {
                    break;
                }
            }
        }
        
        return $output ? $output : '-';
    }
    
    static function Bandwidth($input) {
        
        if ($input == 0) {
            return '-';
        }
        
        $terabyte	= 1024*1024;
        $gigabyte	= 1024;
        $output 	= '';
        if ($input >= ($terabyte/10)) {
            return sprintf("%.2f", round($input/$terabyte, 2)).' TB';
        }
        elseif ($input >= ($gigabyte/10)) {
            return sprintf("%.2f", round($input/$gigabyte, 2)).' GB';
        }
        else {
            return sprintf("%.2f", round($input, 2)).' MB';
        }
    }
	
	static function MB_strpad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = null)
	{
		if (!$encoding) {
			$diff = strlen($input) - mb_strlen($input);
		}
		else {
			$diff = strlen($input) - mb_strlen($input, $encoding);
		}
		return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
	}
}