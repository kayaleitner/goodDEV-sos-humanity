<?php
function create_custom_form_table_if_not_exists() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'job_application_forms'; // Define your table name

    // Check if the table already exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(20),
            position_applied varchar(255),
            submission_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            additional_data longtext,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Hook to run the table creation function after the theme is set up
add_action('after_setup_theme', 'create_custom_form_table_if_not_exists');
