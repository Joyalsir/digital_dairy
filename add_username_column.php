<?php
include('includes/config.php');

// Add username column to farmers table
$sql = "ALTER TABLE farmers ADD COLUMN username VARCHAR(20) UNIQUE AFTER name";

if (mysqli_query($con, $sql)) {
    echo "Username column added successfully to farmers table.\n";

    // Update existing farmers to have usernames based on their names
    $update_sql = "UPDATE farmers SET username = LOWER(REPLACE(SUBSTRING(name, 1, 10), ' ', '_')) WHERE username IS NULL OR username = ''";

    if (mysqli_query($con, $update_sql)) {
        echo "Existing farmers updated with usernames.\n";

        // Handle potential duplicate usernames by appending numbers
        $duplicate_sql = "SELECT username, COUNT(*) as count FROM farmers GROUP BY username HAVING count > 1";
        $duplicate_result = mysqli_query($con, $duplicate_sql);

        if ($duplicate_result && mysqli_num_rows($duplicate_result) > 0) {
            while ($row = mysqli_fetch_assoc($duplicate_result)) {
                $base_username = $row['username'];
                $counter = 1;

                // Update duplicate usernames
                $update_duplicates = "UPDATE farmers SET username = CONCAT('$base_username', '_', id) WHERE username = '$base_username' AND id NOT IN (SELECT MIN(id) FROM farmers WHERE username = '$base_username')";
                mysqli_query($con, $update_duplicates);
            }
            echo "Duplicate usernames resolved.\n";
        }
    } else {
        echo "Error updating existing farmers: " . mysqli_error($con) . "\n";
    }
} else {
    echo "Error adding username column: " . mysqli_error($con) . "\n";
}

echo "Database update completed!\n";
?>
