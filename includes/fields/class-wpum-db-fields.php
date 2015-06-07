<?php
/**
 * Fields DB class
 * This class is for interacting with the fields database table
 *
 * @copyright   Copyright (c) 2015, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPUM_DB_Field_Groups Class
 *
 * @since 1.0.0
 */
class WPUM_DB_Fields extends WPUM_DB {

	/**
	 * Get things started
	 *
	 * @access  public
	 * @since   1.0.0
	*/
	public function __construct() {

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'wpum_fields';
		$this->primary_key = 'id';
		$this->version     = '1.0';

	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 * @since   1.0.0
	*/
	public function get_columns() {
		return array(
			'id'                      => '%d',
			'group_id'                => '%d',
			'type'                    => '%s',
			'name'                    => '%s',
			'description'             => '%s',
			'field_order'             => '%d',
			'is_required'             => '%s',
			'can_delete'              => '%s',
			'default_visibility'      => '%s',
			'allow_custom_visibility' => '%s',
			'options'                 => '%s'
		);
	}

	/**
	 * Get default column values
	 *
	 * @access  public
	 * @since   1.0.0
	*/
	public function get_column_defaults() {
		return array(
			'id'                      => false,
			'group_id'                => false,
			'type'                    => '',
			'name'                    => '',
			'description'             => '',
			'field_order'             => false,
			'is_required'             => false,
			'can_delete'              => true,
			'default_visibility'      => 'public',
			'allow_custom_visibility' => 'disallowed',
			'options'                 => false
		);
	}

	/**
	 * Create the table
	 *
	 * @access  public
	 * @since   1.0.0
	*/
	public function create_table() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE " . $this->table_name . " (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		group_id bigint(20) unsigned NOT NULL,
		type varchar(150) NOT NULL,
		name varchar(150) NOT NULL,
		description longtext NOT NULL,
		field_order bigint(20) NOT NULL DEFAULT '0',
		is_required tinyint(1) NOT NULL DEFAULT '0',
		can_delete tinyint(1) NOT NULL DEFAULT '1',
		default_visibility varchar(150) NOT NULL DEFAULT 'public',
		allow_custom_visibility varchar(150) NOT NULL DEFAULT 'disallowed',
		options longtext DEFAULT NULL,
		PRIMARY KEY (id),
		KEY group_id (group_id),
		KEY field_order (field_order),
		KEY can_delete (can_delete),
		KEY is_required (is_required)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $this->table_name . '_db_version', $this->version );
	}

}