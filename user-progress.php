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
}

if(class_exists('UserProgressPlugin')) {
  $userProgressPlugin = new UserProgressPlugin();
}

function cronstarter_activation() {
  if( !wp_next_scheduled( 'user_progress_update' ) ) {
    wp_schedule_event( time(), 'everyminute', 'user_progress_update' );
  }
}

function cron_add_minute( $schedules ) {
    $schedules['everyminute'] = array(
	    'interval' => 60,
	    'display' => __( 'Once Every 60s' )
    );
    return $schedules;
}


function cronstarter_deactivate() {
	$timestamp = wp_next_scheduled ('user_progress_update');
	wp_unschedule_event ($timestamp, 'user_progress_update');
}

function update_user_progression() {
  require_once plugin_dir_path(__FILE__) . "/src/ProgressionManager.php";
  
  $manager = new ProgressionManager();
  $manager->updateProgressions();
}

/**
 * FILTERS
 */
add_filter( 'cron_schedules', 'cron_add_minute' );

/**
 * HOOKS
 */
register_deactivation_hook(__FILE__, array($userProgressPlugin, 'deactivate'));
register_deactivation_hook (__FILE__, 'cronstarter_deactivate');

register_activation_hook(__FILE__, array($userProgressPlugin, 'activate'));

/**
 * ACTIONS
 */
add_action ('user_progress_update', 'update_user_progression');
add_action('wp', 'cronstarter_activation');
