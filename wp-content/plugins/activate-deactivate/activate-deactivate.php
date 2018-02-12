<?php

/*
	Plugin Name: Activate - Deactivate Plugin
	Plugin URI: https://perishablepress.com/dashboard-widgets-suite/
	Description: Example demonstrating activation, deactivation and uninstall hooks.
	Tags: dashboard, widget, widgets, user notes, notes, todo, memo, rss, social, server, debug, log
	Author: Shaylee Hansen
	Contributors: specialk
	Author URI: https://plugin-planet.com/
  Version: 1.0
	Text Domain: activate-deactivate-plugin
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


// do stuff on activation
  function myplugin_on_activation(){
    if(!current_user_can('activate_plugins')) return;
      add_option('myplugin_posts_per_page'. 10);
      add_option('myplugin_show_welcome_page', true);

  }
  register_activation_hook(__FILE__, 'myplugin_on_activation');

  function myplugin_on_deactivation(){
    if(!current_user_can('activate_plugins')) return;
      flush_rewrite_rules();
  }
  register_deactivation_hook(__FILE__, 'myplugin_on_deactivation');


// do stuff on uninstall
  function myplugin_on_uninstall(){
    if(! current_user_can('activate_plugins')) return;

    delete_option('myplugin_posts_per_page',10);
    delete_option('myplugin_show_wecome_page', true);


  }
  register_uninstall_hook(__FILE__, 'myplugin_on_uninstall');


?>
