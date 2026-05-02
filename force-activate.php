<?php
require_once('../../../wp-load.php');

if ( ! current_user_can( 'manage_options' ) ) {
    die('Unauthorized');
}

update_option( 'tokoku_license_key', 'TK-LOCAL-HOST-10005-FORCE' );
update_option( 'tokoku_license_status', 'activated' );

echo "License Force-Activated for http://localhost:10005/\n";
echo "Status: " . get_option('tokoku_license_status') . "\n";
echo "Key: " . get_option('tokoku_license_key') . "\n";
?>
