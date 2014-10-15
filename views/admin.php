<?php

/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Post_to_Social
 * @author    Steve Taylor
 * @license   GPL-2.0+
 */

?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php if ( isset( $_GET['done'] ) ) { ?>
		<div class="updated"><p><strong><?php _e( 'Settings updated successfully.' ); ?></strong></p></div>
	<?php } ?>

	<form method="post" action="">

		<?php wp_nonce_field( $this->plugin_slug . '_settings', $this->plugin_slug . '_settings_admin_nonce' ); ?>

		<h3><?php _e( 'Twitter', $this->plugin_slug ); ?></h3>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="<?php echo $this->plugin_slug . '-twitter_consumer_key'; ?>"><?php _e( 'Consumer key', $this->plugin_slug ); ?></label></th>
					<td><input name="twitter_consumer_key" id="<?php echo $this->plugin_slug . '-twitter_consumer_key'; ?>" class="regular-text" value="<?php echo esc_attr( $this->settings['twitter_consumer_key'] ); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="<?php echo $this->plugin_slug . '-twitter_consumer_secret'; ?>"><?php _e( 'Consumer secret', $this->plugin_slug ); ?></label></th>
					<td><input name="twitter_consumer_secret" id="<?php echo $this->plugin_slug . '-twitter_consumer_secret'; ?>" class="regular-text" value="<?php echo esc_attr( $this->settings['twitter_consumer_secret'] ); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="<?php echo $this->plugin_slug . '-twitter_access_token'; ?>"><?php _e( 'Access token', $this->plugin_slug ); ?></label></th>
					<td><input name="twitter_access_token" id="<?php echo $this->plugin_slug . '-twitter_access_token'; ?>" class="regular-text" value="<?php echo esc_attr( $this->settings['twitter_access_token'] ); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="<?php echo $this->plugin_slug . '-twitter_access_token_secret'; ?>"><?php _e( 'Access token secret', $this->plugin_slug ); ?></label></th>
					<td><input name="twitter_access_token_secret" id="<?php echo $this->plugin_slug . '-twitter_access_token_secret'; ?>" class="regular-text" value="<?php echo esc_attr( $this->settings['twitter_access_token_secret'] ); ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Post types', $this->plugin_slug ); ?></th>
					<td>
						<?php foreach ( $this->get_potential_post_types() as $post_type ) { ?>
							<label for="<?php echo $this->plugin_slug . '-twitter_post_type_' . $post_type; ?>"><input type="checkbox" name="twitter_post_types[]" id="<?php echo $this->plugin_slug . '-twitter_post_type_' . $post_type; ?>"<?php checked( in_array( $post_type, $this->settings['twitter_post_types'] ) ); ?>> <?php echo $post_type; ?></label>&nbsp;&nbsp;&nbsp;
						<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save settings"></p>

	</form>

</div>
