<?php
/*
Plugin Name: get-age-plugin
Plugin URI: 
Description: Adds a shortcode to calculate age. Usage: [get_age year="YYYY" month="MM" day="DD"]
Version: 1.1
Author: Lester Reandino
Author URI: 
License: GPLv2 or later
*/


/**
 * Register the 'get_age' shortcode with WordPress.
 */
add_shortcode( 'get_age',  function ( $atts ) {
	return Get_Age_Class::calculate_age( $atts );
} );


/**
 * A static namespace for the WordPress shortcode 'get_age'.
 * 
 * @author Lester Reandino
 *
 */
class Get_Age_Class {
	/**
	 * The callback for the shortcode to calculate age.
	 * 
	 * @param unknown $atts shortcode attributes
	 * @return string formatted age result
	 */
	public static function calculate_age( $atts ) {
		// parse and validate input
		extract( shortcode_atts( array(
			'year' => null,
			'month' => null,
			'day' => null,
		), $atts ) );

 		$errors = '';
 		if ( $year == null ) {
 			$errors .= ' ERROR: no year value. ';
 		}
 		if ( $month == null || $month > 12) {
			$errors .= ' ERROR: invalid month value. ';
		}
	   if ( $day == null || $day > 31) {
		   $errors .= ' ERROR: invalid day value. ';
	   }
		if ( "" !== $errors ) {
			return $errors;
		} 
		
		// calculate age		
		$age_interval = date_diff( new DateTime(), new DateTime( "{$year}-{$month}-{$day}" ) );
		
		// format and return result
		return Get_Age_Class::format_age( $age_interval );
	}

	
	/**
	 * Add an appropriate time unit of measurement to the given 'age interval'. It
	 * uses some fuzzy logic that selects the appropriate unit (from 'days',
	 * 'weeks', 'months' or 'years') to measure time.
	 * 
	 * @param DateInterval $age_interval time difference.
	 * @return string formatted age result
	 */
	private static function format_age( $age_interval ) {
		$year = $age_interval->format( '%Y' );
		if ( $year >= 2 ) {
			return $year . ' years';
		}
		
		$month = $age_interval->format( '%m' );
		if ( $year == 1 ) { 
			$month += 12; 
		}
		if ( $month >= 2 ) { 
			return $month . ' months'; 
		}
		
		$day = $age_interval->format( '%d' );
		if ( $month == 1 && $day <= 6 ) {
			return '1 month';
		}

		$day = $age_interval->days;
		if ( $day >= 14 ) {
			return floor( $day / 7 ) . ' weeks';
		}
		if ( $day == 7 ) {
			return '1 week';
		}
		if ( $day >= 2 ) {
			return $day . ' days';
		}
		return '1 day';
	}

}