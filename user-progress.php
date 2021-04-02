<?php

/**
 * @package UserProgress
 */

/*

Plugin Name: User progress plugin

Description: Extend Favorites plugin. Handle user progression arround comments, and likes

Version: 1.0.0

Author: james.standbridge.git@gmail.com

Author URI: https://github.com/JamesStandbridge

Licence: GPLv2 or ob_deflatehandler

Text Domain: UserProgression
*/

defined('ABSPATH') or die("Impossible");

class UserProgressPlugin
{
  function activate() {
    global $wpdb;
    $table_name = $wpdb->prefix.'users';

    $wpdb->query("ALTER TABLE $table_name ADD user_progress_points BIGINT NOT NULL");


    cronstarter_activation();
  }

  function deactivate() {
    global $wpdb;
    $table_name = $wpdb->prefix.'users';

    $wpdb->query("ALTER TABLE $table_name  DROP COLUMN user_progress_points");
  }

  function uninstall() {

  }

  function cronstarter_activation() {
    if( !wp_next_scheduled( 'updateUserProgress' ) ) {
      wp_schedule_event( time(), 'daily', 'updateUserProgress' );
    }
  }

  // and make sure it's called whenever WordPress loads
  add_action('wp', 'cronstarter_activation');
}

if(class_exists('UserProgressPlugin')) {
  $userProgressPlugin = new UserProgressPlugin();
}



//activation
register_activation_hook(__FILE__, array($userProgressPlugin, 'activate'));

//deactivation
register_deactivation_hook(__FILE__, array($userProgressPlugin, 'deactivate'));
