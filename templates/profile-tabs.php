<?php
/**
 * WPUM Template: Profile tabs.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Get current tab slug
$current_tab_slug = wpum_get_current_profile_tab();

// Get keys of the tabs
// Needed to show the first tab if on /profile/
$all_tabs = array_keys( $tabs );

?>

<div class="wpum-profile-tabs-holder">

	<!-- Loop through each available tab -->
	<ul class="wpum-profile-tabs">
		<?php foreach ($tabs as $tab) : ?>
			<li class="wpum-tab-<?php echo $tab['id'];?> <?php echo ($current_tab_slug == $tab['slug'] ? 'active' : '') ?>"><a href="<?php echo wpum_get_profile_tab_permalink( $user_data, $tab );?>"><?php echo $tab['title'];?></a></li>
		<?php endforeach; ?>
	</ul>
	<!-- end tabs -->

</div>

<div class="wpum-profile-tabs-content">

	<?php 

	// Display tabs content.	
	// Check that the tab exists or - null if we're on /profile/ page.
	if( $current_tab_slug === null || wpum_profile_tab_exists( $current_tab_slug ) ) {

		switch ( $current_tab_slug ) {
			case null: // Return first tab if null - meaning we're on /profile/ page
				do_action( "wpum_profile_tab_content_{$all_tabs[0]}" );
				break;
			case $current_tab_slug:
				do_action( "wpum_profile_tab_content_{$current_tab_slug}" );
				break;
			default:
				# nothing here...
				break;
		}

	// Display not found error if tab doesn't exist
	} else {

		get_wpum_template( 'content-not-found.php' );

	}

	?>	

</div>