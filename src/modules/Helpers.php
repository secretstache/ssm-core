<?php

namespace SSM\Core;

class Helpers {

    /**
     * Check if user is member of SSM
     */
	public static function isSSM( $user_id )
	{

		$members = get_option("ssm_core_team_members") ? get_option("ssm_core_team_members") : array();

		return ( in_array( $user_id, $members ) ) ? true : false;

	}

}