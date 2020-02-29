<?php

namespace SSM\Core;

class Helpers {

	/**
     * Control the number of words returned
     */
    public static function limitWords( $string, $word_limit, $ellipses = true )
    {

        $word_limit = (int) $word_limit;

        $string = preg_replace("/<img[^>]+\>/i", "", $string);
        $string = preg_replace("/<iframe[^>]+\>/i", "", $string);

        $string = strip_shortcodes( $string );

        $words = explode(" ", $string);

        if ( count($words) >= $word_limit ) {
            $excerpt = implode(" ", array_slice($words, 0, $word_limit));
            $excerpt .= ( $ellipses ) ? "<span class=\"elipses\">...</span>" : "...";
        } else {
            $excerpt = implode(" ", $words);
        }

        return $excerpt;

	}
	
	/**
     * Sanitize HTML Classes
     */
	public static function sanitizeHtmlClasses( $class, $fallback = null )
	{

        // Explode it, if it"s a string
        if ( is_string( $class ) ) {
            $class = explode(" ", $class);
        }

        if ( is_array( $class ) && count( $class ) > 0 ) {

            $class = array_map("sanitize_html_class", $class);
            return implode(" ", $class);

        } else {

            return sanitize_html_class( $class, $fallback );

        }

	}

    /**
     * Check if user is member of SSM
     */
	public static function isSSM( $user_id )
	{

		$members = get_option("ssm_core_team_members") ? get_option("ssm_core_team_members") : array();

		return ( in_array( $user_id, $members ) ) ? true : false;

	}

}