<?php
include_once "toevoeging.php";

// Connect to the database and obtain the PDO connection object
$conn = connectToDatabase();

// Instantiate Authentication class
$auth = new Authentication($conn);

// Assume you have a user ID from the login process
$loggedInUserId = 1;

// Get user information from the database
$userInfo = $auth->getUserInfo($loggedInUserId);

if ($userInfo) {
    // User found, create User object based on retrieved information
    $user = new User($userInfo['ID'], $userInfo['MayManage'] ? 'manager' : 'employee'); // Adjust the role accordingly
    
    $users = $user->dropDownUsers();
    $user->edits($projectIdForUser);
} else {
    // User not found or authentication failed
    echo "Authentication failed.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if($loggedInUserId ==1 ){
        
    
    ?>
    <h2>Maak nieuwe project</h2>
    <form action="" method="post">
        <label for="title">Name</label>
        <input type="text" name="title" required>
        <br>
        <label for="start_date">Start Date</label>
        <input type="date" name="start_date" required>
        <br>
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" required>
        <br>
        <label for="code">Code</label>
        <input type="number" name="code" required>
        <br>
        <label for="active">active</label>
        <input type="number"name="active" maxlength="1" required>
        <br>
        <label for="actuel">actuel</label>
        <input type="number" name="actuel" required>
        <br>
        <label for="users">users</label>
        <select name="user_id" required>
         <!-- Fetch and display users from the database -->
        <?php
        // Loop through the users and create options for the select element
        foreach ($users as $user) {
            echo "<option value='" . $user['ID'] . "'>" . $user['Name'] . "</option>";
        }
        ?>
        </select>
        <br>
        <label for="MaxHours">Max Hours</label>
        <input type="number" name="MaxHours" required>
        <br>
        <label for="description">description</label>
        <input type="text" name="description" required>
        <br>
        <button type="submit" name="submit">submit</button>
    </form>
    <?php }?>
</body>
</html>