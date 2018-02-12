<?php

/*
	Plugin Name: Pluggable Functions
	Description: Basic example demonstrating pluggable functions.
	Author: Shaylee Hansen
  Version: 1.0
	Text Domain: pluggable-functions
	Domain Path: /languages
	License: GPL v2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version
	2 of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/

	Copyright 2017 Monzilla Media. All rights reserved.
*/

// wp-includes/pluggable.php
function wp_logout() {
	myplugin_destroy_current_session();
	myplugin_clear_auth_cookie();
  myplugin_custom_logout();

	/**
	 * Fires after a user is logged-out.
	 *
	 * @since 1.5.0
	 */
	do_action( 'wp_logout' );
}

// customize logout function

function myplugin_custom_logout(){
  // do something when the user logs out...



}
