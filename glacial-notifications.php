<?php
/**
 * Glacial Notifications
 *
 * @package       Glacial_Notifications
 * @author        Glacial Multimedia
 * @license       gplv2
 * @version       1.0.3
 *
 * @wordpress-plugin
 * Plugin Name:   Glacial Notifications
 * Plugin URI:    https://glacial.com
 * Description:   A simple plugin to add notifications to your site.
 * Version:       1.0.3
 * Author:        Glacial Multimedia
 * Author URI:    https://glacial.com
 * Text Domain:   glacial-notifications
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: https://github.com/Glacial-Web/glacial-notifications
 * Primary Branch: develop
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GLACIAL_NOTIFICATIONS_NAME', 'Glacial Notifications' );
define( 'GLACIAL_NOTIFICATIONS_VERSION', '1.0.0' );
define( 'GLACIAL_NOTIFICATIONS_ASSET_VERSION', time() );
define( 'GLACIAL_NOTIFICATIONS_PLUGIN_FILE', __FILE__ );
define( 'GLACIAL_NOTIFICATIONS_PLUGIN_DIR', plugin_dir_path( GLACIAL_NOTIFICATIONS_PLUGIN_FILE ) );
define( 'GLACIAL_NOTIFICATIONS_PLUGIN_URL', plugin_dir_url( GLACIAL_NOTIFICATIONS_PLUGIN_FILE ) );

if ( ! class_exists( 'Glacial_Notifications' ) ) {

	class Glacial_Notifications {

		/**
		 * Static property to hold our singleton instance
		 *
		 */
		static $instance = false;

		/**
		 * This is our constructor
		 *
		 * @return void
		 */
		private function __construct() {

			$this->includes();
		}

		public static function getInstance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function includes() {
			require_once GLACIAL_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-glacial-notifications-assets.php';
			require_once GLACIAL_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-glacial-notifications-settings.php';
			require_once GLACIAL_NOTIFICATIONS_PLUGIN_DIR . 'includes/class-glacial-notifications-output.php';
		}

	}
}

$glacial_notifications = Glacial_Notifications::getInstance();
