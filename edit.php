<?php
include_once "db.php";
include "toevoeging.php";
// Connect to the database and obtain the PDO connection object
$conn = connectToDatabase();

// Instantiate Authentication class
$auth = new Authentication($conn);

// Log in info
$loggedInUserId = 1;

// Get user information from the database
$userInfo = $auth->getUserInfo($loggedInUserId);

if ($userInfo) {
    // User found, create User object based on retrieved information
    $user = new User($userInfo['ID'], $userInfo['MayManage'] ? 'manager' : 'employee'); // Adjust the role accordingly

    // Get the project ID from the form or URL parameter
    $projectId = isset($_POST['project_id']) ? $_POST['project_id'] : (isset($_GET['projectId']) ? $_GET['projectId'] : null);

    if ($projectId) {
        // Call the displaySelectedProject function
        $selectedProject = $user->displaySelectedProject($projectId);
        // var_dump($selectedProject);
    } else {
        echo "Project ID is not set or invalid.";
    }
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
    <h2>Update</h2>
    <form action="overzicht.php" method="post">
     <input type="hidden" name="project_id" value="<?php echo $selectedProject['ProjectID']; ?>">
    
     <label for="title">Project Name:</label>
    <input type="text" id="title" name="title" value="<?php echo $selectedProject['Title']; ?>" required><br>

    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" value="<?php echo $selectedProject['StartDT']; ?>" required><br>

    <label for="end_date">End Date</label>
    <input type="date" name="end_date" value="<?php echo $selectedProject['EndDT']; ?>" required><br>

    <label for="code">Code</label>
    <input type="number" name="code" value="<?php echo $selectedProject['Code']?>" required><br>

    <label for="active">Active:</label>
    <input type="number" id="active" name="active" value="<?php echo $selectedProject['Active']; ?>" required><br>

    <label for="actual">Actuel:</label>
    <input type="number" id="actuel" name="actuel" value="<?php echo $selectedProject['Actual']; ?>" required><br>

    <!-- <label for="user">employee:</label> -->
    <!-- <select name="user_id" required>  -->
         <!-- Fetch and display users from the database -->
        <?php
        // Loop through the users and create options for the select element
        // foreach ($users as $user) {
        //     echo "<option value='" . $user['ID'] . "'>" . $user['Name'] . "</option>";
        // }
        ?>
         <!-- </select> -->
    <br>

    <label for="maxHours">Max Hours:</label>
    <input type="number" id="maxHours" name="maxHours" value="<?php echo $selectedProject['MaxHours']; ?>"><br>

    <label for="description">Description:</label>
    <input type="textarea" id="description" name="description" value="<?php echo $selectedProject['Description']; ?>" required><br>
    <input type="submit" name="submitUpdate" value="Update project">
</form>
</body>
</html>

<?php
    

?>