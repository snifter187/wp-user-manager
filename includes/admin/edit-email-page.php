<?php
/**
 * Edit Email Page
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$email_id = esc_attr( $_GET['email-id'] );
$email_title = esc_attr( $_GET['email-title'] );
$get_emails = get_option('wpum_emails');
$this_email = $get_emails[ $email_id ];
?>
<div class="wrap">

	<h2 class="wpum-page-title"><?php printf( __( 'WP User Manager - Editing "%s"', 'wpum' ), $email_title ); ?> <a href="<?php echo admin_url( 'users.php?page=wpum-settings&tab=emails' );?>" class="add-new-h2"><?php _e('Back to settings page &raquo;');?></a></h2>

	<form id="wpum-edit-email" action="" method="post">
		<table class="form-table">
			<tbody>
				
				<tr>
					<th scope="row" valign="top">
						<label for="wpum-email-subject"><?php _e( 'Email Subject:' ); ?></label>
					</th>
					<td>
						<input name="subject" id="wpum-email-subject" type="text" value="<?php echo esc_attr( stripslashes( $this_email['subject'] ) ); ?>" style="width: 300px;"/>
						<p class="description"><?php _e( 'The subject line of the email' ); ?></p>
					</td>
				</tr>
				<tr>
				<th scope="row" valign="top">
					<label for="wpum-notice-message"><?php _e( 'Email Message:' ); ?></label>
				</th>
				<td>
					<?php wp_editor( wpautop( wp_kses_post( wptexturize( $this_email['message'] ) ) ), 'message', array( 'textarea_name' => 'message', 'media_buttons' => false, 'textarea_rows' => 10 ) ); ?>
					<p class="description"><?php _e( 'The email message to be sent into the notification. The following template tags can be used in the message:' ); ?></p>
				</td>
			</tr>
				
			</tbody>
		</table>
		
		<input type="hidden" name="wpum-action" value="edit_email"/>
		<input type="hidden" name="email_id" value="<?php echo esc_attr( $email_id ); ?>"/>
		<input type="hidden" name="wpum-email-nonce" value="<?php echo wp_create_nonce( 'wpum_email_nonce' ); ?>"/>
		
		<?php submit_button(); ?>

	</form>

</div>