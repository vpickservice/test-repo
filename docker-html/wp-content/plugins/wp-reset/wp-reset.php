<?php
/*
  Plugin Name: WP Reset
  Plugin URI: https://wordpress.org/plugins/wp-reset/
  Description: Reset the site to default installation values without modifying any files. Deletes all customizations and content.
  Version: 1.11
  Author: WebFactory Ltd
  Author URI: https://www.webfactoryltd.com/
  Text Domain: wp-reset

  Copyright 2015 - 2018  Web factory Ltd  (email: wpreset@webfactoryltd.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( ! defined( 'ABSPATH' ) ){
	exit;
}


define( 'REACTIVATE_THE_WP_RESET', true );


if ( is_admin() ) {

class WPReset {
  static $version = 1.11;


	function __construct() {
		add_action( 'admin_menu', array( &$this, 'add_tools_page' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'wp_before_admin_bar_render', array( &$this, 'link_admin_bar' ) );
    add_filter( 'admin_footer_text', array( &$this, 'admin_footer_text') );
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'plugin_action_links') );
	} // WPReset


	// Checks wp_reset post value and performs an installation.
	function admin_init() {
		global $current_user, $wpdb;

    if ( !current_user_can('administrator') ) {
      return;
    }

		$wp_reset_confirm = ( isset( $_POST['wp_reset_confirm'] ) && $_POST['wp_reset_confirm'] == 'reset' ) ? true : false;
		$valid_nonce = ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wp_reset' ) ) ? true : false;

		if ( $wp_reset_confirm && $valid_nonce ) {
			@require_once ABSPATH . '/wp-admin/includes/upgrade.php';

			$blogname = get_option( 'blogname' );
			$blog_public = get_option( 'blog_public' );
      $wplang = get_option( 'wplang' );
      $siteurl = get_option ( 'siteurl' );
      $home = get_option ( 'home' );

			$prefix = str_replace( '_', '\_', $wpdb->prefix );
			$tables = $wpdb->get_col( "SHOW TABLES LIKE '{$prefix}%'" );
			foreach ( $tables as $table ) {
				$wpdb->query( "DROP TABLE $table" );
			}

			$result = wp_install( $blogname, $current_user->user_login, $current_user->user_email, $blog_public, '', '', $wplang);
			$user_id = $result['user_id'];

			$query = $wpdb->prepare( "UPDATE {$wpdb->users} SET user_pass = %s, user_activation_key = '' WHERE ID = %d LIMIT 1", array($current_user->user_pass, $user_id));
			$wpdb->query( $query );

      update_option('siteurl', $siteurl);
      update_option('home', $home);

			if ( get_user_meta( $user_id, 'default_password_nag' ) )
			  update_user_meta( $user_id, 'default_password_nag', false );

			if ( get_user_meta( $user_id, $wpdb->prefix . 'default_password_nag' ) )
				update_user_meta( $user_id, $wpdb->prefix . 'default_password_nag', false );


			if ( defined( 'REACTIVATE_THE_WP_RESET' ) && REACTIVATE_THE_WP_RESET === true )
				@activate_plugin( plugin_basename( __FILE__ ) );


			wp_clear_auth_cookie();
			wp_set_auth_cookie( $user_id );

			wp_redirect( admin_url() . "?wp-reset=wp-reset" );
			exit();
		}

		if ( array_key_exists( 'wp-reset', $_GET ) && stristr( $_SERVER['HTTP_REFERER'], 'wp-reset' ) )
			add_action( 'admin_notices', array( &$this, 'successfully_reset' ) );
	} // admin_init


  // add settings link to plugins page
  static function plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('tools.php?page=wp-reset') . '" title="' . __('Reset WordPress', 'wp-reset') . '">' . __('Reset WordPress', 'wp-reset') . '</a>';

    array_unshift($links, $settings_link);

    return $links;
  } // plugin_action_links


  // test if we're on plugin's page
  function is_plugin_page() {
    $current_screen = get_current_screen();

    if ($current_screen->id == 'tools_page_wp-reset') {
      return true;
    } else {
      return false;
    }
  } // is_plugin_page


  // additional powered by text in admin footer; only on UCP page
  function admin_footer_text($text) {
    if (!$this->is_plugin_page()) {
      return $text;
    }

    $text = '<i><a href="https://wordpress.org/plugins/wp-reset/" title="' . __('Visit WP Reset page for more info', 'wp-reset') . '" target="_blank">WP Reset</a> v' . self::$version . ' by <a href="https://www.webfactoryltd.com/" title="' . __('Visit our site to get more great plugins', 'wp-reset'). '" target="_blank">WebFactory Ltd</a>.</i> '. $text;

    return $text;
  } // admin_footer_text


	// admin_menu action hook operations & Add the settings page
	function add_tools_page() {
	  $hook = add_management_page( 'WP Reset', 'WP Reset', 'administrator', 'wp-reset', array( &$this, 'admin_page' ) );
		add_action( "admin_print_scripts-{$hook}", array( &$this, 'admin_script' ) );
		add_action( "admin_footer-{$hook}", array( &$this, 'footer_script' ) );
	} // add_tools_page


  function load_textdomain() {
    load_plugin_textdomain( 'wp-reset' );
  } // load_textdomain


	function link_admin_bar() {
		global $wp_admin_bar;

    if (!current_user_can('administrator')) {
      return;
    }

		$wp_admin_bar->add_menu(
			array(
				'parent' => 'site-name',
				'id'     => 'wp-reset',
				'title'  => 'WP Reset',
				'href'   => admin_url( 'tools.php?page=wp-reset' )
			)
		);
	} // link_admin_bar


	// Inform the user that WordPress has been successfully reset
	function successfully_reset() {
		global $current_user;

		echo '<div id="message" class="updated fade"><p>' . sprintf( __( '<b>Site has been reset</b> to default settings. User "%s" was restored with the password unchanged.', 'wp-reset' ), $current_user->user_login ) . '</p></div>';

	} // successfully_reset


	function admin_script() {
    $localize = array('invalid_confirmation' => __('Invalid confirmation. Please type "reset" in the confirmation field.', 'wp-reset'),
                      'confirm1' => __('Clicking "OK" will reset your site to default values. All content will be lost. There is NO UNDO.', 'wp-reset'),
                      'confirm2' => __('Click "Cancel" to abort.', 'wp-reset'));

    wp_enqueue_script( 'jquery' );
    wp_localize_script('jquery', 'wp_reset', $localize);
	} // admin_script


	function footer_script() {
	?>
	<script type="text/javascript">
		jQuery('#wp_reset_submit').click(function(e) {
			if ( jQuery('#wp_reset_confirm').val() == 'reset' ) {
				message = wp_reset.confirm1 + '\n' + wp_reset.confirm2;
				reset = confirm(message);
				if ( reset ) {
					jQuery('#wp_reset_form').submit();
				} else {
					return false;
				}
			} else {
				alert(wp_reset.invalid_confirmation);
				return false;
			}
		});
	</script>
	<?php
	} // footer_script


	// add_option_page callback operations
	function admin_page() {
		global $current_user, $wpdb;

    if (!current_user_can('administrator')) {
      return;
    }

		if ( isset( $_POST['wp_reset_confirm'] ) && $_POST['wp_reset_confirm'] != 'reset' ) {
      echo '<div class="error fade"><p>' . __('<b>Invalid confirmation code.</b> Please type "reset" in the confirmation field.', 'wp-reset') . '</p></div>';
    }	elseif ( isset( $_POST['_wpnonce'] ) && !wp_verify_nonce( $_POST['_wpnonce'], 'wp_reset' ) ) {
			echo '<div class="error fade"><p>' . __('Something went wrong. Please refresh the page and try again.', 'wp-reset') . '</strong></p></div>';
    }

?>
  <style type="text/css">
  .plain-list {
    margin-top: 5px;
    list-style-type: circle;
    list-style-position: inside;
  }
  .plain-list li {
    text-indent: -18px;
    padding-left: 23px;
    line-height: 23px;
    margin: 0;
  }
  .red {
    color: red;
  }
  .green {
    color: green;
  }
  </style>
<?php
    echo '<div class="wrap">';
		echo '<h1>WP Reset</h1>';

    echo '<div class="card">';
    echo '<h2>' . __('Please read carefully before proceeding. There is NO UNDO!', 'wp-reset') . '</h2>';
    echo '<b class="red">' . __('Resetting will delete:', 'wp-reset') . '</b>';
    echo '<ul class="plain-list">';
    echo '<li>' . __('all posts, pages, custom post types, comments, media entries, users', 'wp-reset') . '</li>';
    echo '<li>' . __('all default WP database tables', 'wp-reset') . '</li>';
    echo '<li>' . sprintf(__('all custom database tables that have the same prefix "%s" as default tables in this installation', 'wp-reset'), $wpdb->prefix) . '</li>';
    echo '</ul>';

    echo '<b class="green">' . __('Resetting will not delete:', 'wp-reset') . '</b>';
    echo '<ul class="plain-list">';
    echo '<li>' . __('media files - they\'ll remain in the <i>wp-uploads</i> folder but will no longer be listed under Media', 'wp-reset') . '</li>';
    echo '<li>' . __('no files are touched; plugins, themes, uploads - everything stays', 'wp-reset') . '</li>';
    echo '<li>' . __('site title, WordPress address, site address, site language and search engine visibility settings', 'wp-reset') . '</li>';
    echo '<li>' . sprintf(__('logged in user "%s" will be restored with the current password', 'wp-reset'), $current_user->user_login) . '</li>';
    echo '</ul>';

    echo '<b>' . __('What happens when I click the Reset button?', 'wp-reset') . '</b>';
    echo '<ul class="plain-list">';
    echo '<li>' . __('you will have to confirm the action one more time because there is NO UNDO', 'wp-reset') . '</li>';
    echo '<li>' . __('everything will be reset; see bullets above for details', 'wp-reset') . '</li>';
    echo '<li>' . __('site title, WordPress address, site address, site language, search engine visibility and current user will be restored', 'wp-reset') . '</li>';
    echo '<li>' . __('you will be logged out, automatically logged in and taken to the admin dashboard', 'wp-reset') . '</li>';
    echo '<li>' . __('WP Reset plugin will be reactivated', 'wp-reset') . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '<div class="card">';
		echo '<h2>' . __('Reset', 'wp-reset') . '</h2>';
		echo '<p>' . __('Type <b>reset</b> in the confirmation field to confirm the reset and then click the "Reset WordPress" button. <b>There is NO UNDO. No backups are made by this plugin.</b>', 'wp-reset') . '</p>';
    echo '<p>' . sprintf(__('While doing work on your site we recommend installing the free <a href="%s" target="_blank">UnderConstructionPage</a> plugin. It helps with SEO and builds trust with visitors.', 'wp-reset'), 'https://wordpress.org/plugins/under-construction-page/') . '</p>';
		echo '<form id="wp_reset_form" action="" method="post" autocomplete="off">';
		wp_nonce_field( 'wp_reset' );
		echo '<input id="wp_reset_confirm" type="text" name="wp_reset_confirm" placeholder="' . esc_attr__('Type in "reset"', 'wp-reset'). '" value="" autocomplete="off"> &nbsp;';
		echo '<input id="wp_reset_submit" type="submit" name="submit" class="button-primary" value="' . __('Reset WordPress', 'wp-reset') . '">';
		echo '</form>';
    echo '</div>';
	  echo '</div>';
	} // admin_page
} // WPReset class

$WPReset = new WPReset();

add_action( 'plugins_loaded', array(&$WPReset, 'load_textdomain' ) );

} // is_admin