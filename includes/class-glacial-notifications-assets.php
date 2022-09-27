<?php
/**
 * Loads all static assets for the plugin.
 *
 * @since         1.0.0
 * @package       Glacial_Notifications
 * @subpackage    Glacial_Notifications/includes
 * @author        Glacial Multimedia
 */

if ( ! class_exists( 'Glacial_Notifications_Assets' ) ) {

	class Glacial_Notifications_Assets {
		/**
		 * Static property to hold our singleton instance
		 *
		 */
		static $instance = false;

		/**
		 * @var false|mixed|null
		 */
		private $options;

		private function __construct() {

			$this->options = get_option( 'glacial_notifications' );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 10 );
			add_action( 'admin_init', array( $this, 'add_editor_styles' ) );
		}

		public static function getInstance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Admin assets
		 *
		 * @return void
		 */
		public function admin_scripts() {

			wp_enqueue_style( 'glacial-notifications-admin-style', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'admin/css/glacial-notifications-admin.css', array(), GLACIAL_NOTIFICATIONS_VERSION, 'all' );
			wp_enqueue_script( 'glacial-notifications-admin-script', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'admin/js/glacial-notifications-admin.js', array( 'jquery' ), GLACIAL_NOTIFICATIONS_VERSION, false );
		}

		/**
		 * Frontend assets
		 * @return void
		 */
		public function front_scripts() {
			wp_enqueue_style( 'glacial-notifications-style', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'public/css/glacial-notifications.css', array(), GLACIAL_NOTIFICATIONS_VERSION, 'all' );
			wp_enqueue_script( 'glacial-notifications-script', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'public/js/glacial-notifications.js', array( 'jquery' ), GLACIAL_NOTIFICATIONS_VERSION, true );
			if ( get_option( 'glacial_notifications_enable_sticky_header' ) ) {
				wp_enqueue_script( 'glacial-notifications-sticky-header', GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'public/js/sticky-header.js', array( 'jquery' ), GLACIAL_NOTIFICATIONS_VERSION, true );
			}
		}

		/**
		 * Add editor styles
		 *
		 * @return void
		 */
		function add_editor_styles() {
			add_editor_style( GLACIAL_NOTIFICATIONS_PLUGIN_URL . 'admin/css/glacial-notifications-editor-styles.css' );
		}
	}
}

$glacial_notifications_assets = Glacial_Notifications_Assets::getInstance();