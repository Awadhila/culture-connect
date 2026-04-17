<?php
require_once 'connection.php'; // Establish the connection to the database
// For now, we close the connection immediately since we aren't running queries yet
require_once 'create_tables.php'; // Ensure tables are created before inserting data
require_once 'insert_test_data.php'; // Insert test data after tables are created
$conn->close();

?>