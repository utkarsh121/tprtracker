<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Client</title>
</head>
<body>
    <h1>Register New Client</h1>
    <form action="process.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="company">Company:</label>
        <input type="text" id="company" name="company" required><br><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
