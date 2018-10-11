<?php

/**
 * Provide a public-facing view for the widget
 *
 * Available vars:
 * $posts, $post, $wp_did_header, $wp_query, $wp_rewrite,
 * $wpdb, $wp_version, $wp, $id, $comment, $user_ID
 *
 * $widget_options = array(
 * 	'messages',
 * 	'username',
 * 	'widget_with',
 * 	'author_photo'
 * );
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.0.0
 *
 * @package    Wptelegram_Widget
 * @subpackage Wptelegram_Widget/public/partials
 */
// This file should primarily consist of HTML with a little bit of PHP.

$atts = 'data-width="' . $widget_options['widget_with'] . '%" ';
if ( ! is_null( $widget_options['author_photo'] ) ) {
	$atts .= 'data-userpic="' . $widget_options['author_photo'] . '"';
}
?>
<div class="wptelegram-widget-wrap container" id="wptelegram-widget-wrap">
	<script async src="https://telegram.org/js/telegram-widget.js?3"></script>
	<?php foreach ( $widget_options['messages'] as $message_id ) : ?>
		<div class="wptelegram-widget-message item">
		<script data-telegram-post="<?php echo sprintf( '%s/%s', $widget_options['username'], $message_id ); ?>" <?php echo $atts; ?>></script>
		</div>
	<?php endforeach; ?>
</div>