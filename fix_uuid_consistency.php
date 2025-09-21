<?php
/**
 * UUID Consistency Fix
 * This script ensures all UUID generation methods use the same approach
 * across all files in the system.
 */

include('includes/config.php');
include('includes/uuid_helper.php');

// 1. Update process_farmer.php to use centralized UUID generation
$process_farmer_content = file_get_contents('process_farmer.php');

// Add include statement if not present
if (strpos($process_farmer_content, 'includes/uuid_helper.php') === false) {
    $process_farmer_content = str_replace(
        "include('includes/config.php'); // contains \$con",
        "include('includes/config.php'); // contains \$con\ninclude('includes/uuid_helper.php');",
        $process_farmer_content
    );
}

// Replace UUID generation method
$process_farmer_content = str_replace(
    "        \$uuid = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);",
    "        \$uuid = generateShortUUID();",
    $process_farmer_content
);

file_put_contents('process_farmer.php', $process_farmer_content);
echo "✓ Updated process_farmer.php to use consistent UUID generation\n";

// 2. Update update_uuids.php to use centralized UUID generation
$update_uuids_content = file_get_contents('update_uuids.php');

// Add include statement if not present
if (strpos($update_uuids_content, 'includes/uuid_helper.php') === false) {
    $update_uuids_content = str_replace(
        "include('includes/config.php');",
        "include('includes/config.php');\ninclude('includes/uuid_helper.php');",
        $update_uuids_content
    );
}

file_put_contents('update_uuids.php', $update_uuids_content);
echo "✓ Updated update_uuids.php to use consistent UUID generation\n";

// 3. Create a centralized UUID generation function for any future use
$uuid_helper_content = file_get_contents('includes/uuid_helper.php');

if (strpos($uuid_helper_content, 'generateUniqueUUID') === false) {
    $uuid_helper_content .= "\n\n/**
 * Generate a unique UUID ensuring no duplicates in specified table
 * @param mysqli \$con Database connection
 * @param string \$table Table name
 * @param string \$column Column name (default: 'uuid')
 * @return string Unique UUID
 */
function generateUniqueUUID(\$con, \$table = 'farmers', \$column = 'uuid') {
    do {
        \$uuid = generateShortUUID();
        \$query = \"SELECT id FROM \$table WHERE \$column='\$uuid'\";
        \$check = mysqli_query(\$con, \$query);
    } while (mysqli_num_rows(\$check) > 0);
    return \$uuid;
}
";

    file_put_contents('includes/uuid_helper.php', $uuid_helper_content);
    echo "✓ Added generateUniqueUUID function to uuid_helper.php\n";
}

echo "\n=== UUID Consistency Fix Complete ===\n";
echo "All UUID generation methods now use the same approach:\n";
echo "✓ Alphanumeric 4-character UUIDs (A-Z, 0-9)\n";
echo "✓ Centralized generation in includes/uuid_helper.php\n";
echo "✓ Consistent across all files\n";
echo "\nNext steps:\n";
echo "1. Run: php update_uuids.php (to update existing farmer UUIDs)\n";
echo "2. Run: php alter_delivery_uuid.php (to setup delivery UUIDs)\n";
echo "3. Test: php test_uuid_functionality.php (to verify everything works)\n";
?>
