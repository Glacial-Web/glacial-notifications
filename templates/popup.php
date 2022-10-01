<?php
/**
 * The template for the sticky header notification.
 *
 *
 * @since         1.0.0
 * @package       Glacial_Notifications
 * @subpackage    Glacial_Notifications/includes
 * @author        Glacial Multimedia
 */

$glacial_notifications_options = get_option( 'glacial_notifications' );

$content = $glacial_notifications_options['content'];
$button  = $glacial_notifications_options['button'];
$colors  = $glacial_notifications_options['colors'];
$style   = 'style="background-color:' . $colors['background'] . '; color:' . $colors['text'] . '"';

$settings = array(
	'cookie'          => $glacial_notifications_options['cookie']['cookie'] ?? 0,
	'closed_duration' => $glacial_notifications_options['cookie']['closed_duration'] ?? 0,
	'front_page_only' => $glacial_notifications_options['front_page_only'],
);

$settings_json = json_encode( $settings );
$data_props    = "data-props=$settings_json";

?>

<div class="glacial-popup" id="glacialPopup" aria-hidden="true" <?php echo $data_props; ?>>
    <div class="glacial-popup-overlay" tabindex="-1" data-micromodal-close>
        <div class="glacial-popup-container" <?php echo $style; ?> role="dialog" aria-modal="true"
             aria-labelledby="modal-1-title">
            <button aria-label="Close modal" data-micromodal-close class="glacial-popup-close-button">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="gla-noti-close-icon"
                     viewBox="0 0 352 512">
                    <path fill="<?php echo $colors['text']; ?>"
                          d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
                </svg>
            </button>
            <div class="glacial-popup-content">
				<?php echo $content; ?>

				<?php if ( $button['text'] ):
					$target = $button['target'] ? '_blank' : '_self'; ?>

                    <a href="<?php echo $button['url']; ?>" class="ui-button"
                       target="<?php echo $target; ?>"><?php echo $button['text']; ?></a>

				<?php endif; ?>
            </div>
        </div>
    </div>
</div>
