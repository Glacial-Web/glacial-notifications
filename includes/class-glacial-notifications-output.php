<?php
/**
 * Grabs the proper template file and outputs it.
 *
 * @since         1.0.0
 * @package       Glacial_Notifications
 * @subpackage    Glacial_Notifications/includes
 * @author        Glacial Multimedia
 */

if ( ! class_exists( 'Glacial_Notifications_Output' ) ) {

	class Glacial_Notifications_Output {

		/**
		 * Static property to hold our singleton instance
		 *
		 */
		static $instance = false;

		/**
		 * @var false|mixed|null
		 */
		private $options;

		private $active;

		private $front_page_only;

		private function __construct() {

			$this->options         = get_option( 'glacial_notifications' );
			$this->active          = $this->options['status'] ?? false;
			$this->front_page_only = $this->options['front_page_only'] ?? false;

			add_action( 'wp_footer', array( $this, 'get_template' ) );
		}

		public static function getInstance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function is_active() {

			if ( $this->active ) {

				/*
				 * If front page only is set to true, only show the notification on the front page.
				 * */
				if ( is_front_page() && $this->front_page_only ) {
					return true;
				}

				/*
				 * If front page is not the only page to show the notification
				 * */
				if ( ! $this->front_page_only ) {
					return true;
				}
			}

			return false;
		}

		public function get_template() {

			if ( $this->is_active() ) {


				$placement = $this->options['placement'];

				if ( $placement == 'sticky-header' ) {
					include GLACIAL_NOTIFICATIONS_PLUGIN_DIR . 'templates/sticky-header.php';
				} else {
					include GLACIAL_NOTIFICATIONS_PLUGIN_DIR . 'templates/popup.php';
				}
			}


		}

		public
		function output() {
		}

	}
}

$glacial_notifications_output = Glacial_Notifications_Output::getInstance();