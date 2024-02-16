<?php
// Function to check if the database setup has already been done
function isDatabaseSetupDone() {
    return file_exists('setup_done.txt');
}

// Function to execute the SQL script to set up the database tables
function setupDatabase($servername, $username, $password, $dbname, $tableprefix) {
    // Create connection
    $conn = new mysqli($servername, $username, $password);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql_create_db) === TRUE) {
        echo "Database created successfully<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
        return false;
    }

    // Select database
    $conn->select_db($dbname);

    // Read SQL script from file
    $sql_script = file_get_contents("db/create_tables.sql");

    // Replace table prefix placeholders in SQL script
    $sql_script = str_replace('%PREFIX%', $tableprefix, $sql_script);

    // Execute SQL script to create table
    if ($conn->multi_query($sql_script) === TRUE) {
        echo "Database setup successful<br>";
        // Create a file to indicate that setup is done
        file_put_contents('setup_done.txt', 'Database setup done');
    } else {
        echo "Error creating database tables: " . $conn->error . "<br>";
        return false;
    }

    // Close connection
    $conn->close();

    return true;
}

// Check if the setup button is clicked
if (isset($_POST['setup'])) {
    $servername = $_POST['servername'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbname = $_POST['dbname'];
    $tableprefix = $_POST['tableprefix'];

    // Execute database setup
    if (setupDatabase($servername, $username, $password, $dbname, $tableprefix)) {
        $setupStatus = "Success";
    } else {
        $setupStatus = "Failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My PHP App</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="content">
    <h1>Welcome to My PHP App</h1>
    <p>This is a simple PHP web application.</p>

    <?php if (!isDatabaseSetupDone()): ?>
    <!-- Show setup form if database setup is not done -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="servername">Server Name:</label>
        <input type="text" id="servername" name="servername" required><br><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br><br>
        <label for="dbname">Database Name:</label>
        <input type="text" id="dbname" name="dbname" required><br><br>
        <label for="tableprefix">Table Prefix:</label>
        <input type="text" id="tableprefix" name="tableprefix" value="app_" required><br><br>
        <button type="submit" name="setup">First run setup</button>
    </form>
    <?php endif; ?>

    <?php if (isset($setupStatus)): ?>
    <!-- Show setup status -->
    <p>Setup <?php echo $setupStatus; ?></p>
    <?php endif; ?>
</div>

</body>
</html>
