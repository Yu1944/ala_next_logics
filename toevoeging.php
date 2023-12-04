<?php 
include_once "db.php";

class User {
    private $conn;
    private $userId;
    private $userRole;

    public function __construct($userId, $userRole) {
        $this->conn = connectToDatabase(); 
        $this->userId = $userId;
        $this->userRole = $userRole;
    }

    public function edits($projectId) {
        try {
            if ($this->userRole === 'manager') {
                // Managers can create and update/deactivate projects
                $this->makeNewProject($projectId);
                $this->updateProject($projectId);
                $this->manageTime($projectId);
                $this->dropDownUsers($projectId);
                // Show manager dashboard or project list
                $this->showManagerDashboard();
            } else {
                // Employees can only manage their own time
                $this->manageTime($projectId);

                // Show employee dashboard or project list
                $this->showEmployeeDashboard();
            }
        } catch (Exception $e) {
            // Handle exceptions (e.g., log or display an error message)
            echo "Error: " . $e->getMessage();
        }
    }

    public function getProjects() {
        $projectManager = new ProjectManager($this->conn);
        return $projectManager->getProjectsForUser($this->userId);
    }

    public function dropDownUsers(){
            // Fetch users
            $sql = "SELECT ID, Name FROM users";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the fetched users after the HTML form
        return $users;
    }

    private function showManagerDashboard() {
    
        // Display manager dashboard or project list
        echo "Manager Dashboard - All Projects";
    
        $sql = "SELECT 
            projectdata.*, projects.*, users.*,
            projects.title AS project_name, 
            users.Name AS user_name
        FROM 
            projectdata 
        JOIN 
            projects ON projectdata.ProjectID = projects.ID
        JOIN 
            users ON projectdata.UserID = users.ID";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Display the projects 
            if ($projects) {
                echo "<ul>";
                foreach ($projects as $project) {
                    echo "<li>Project ID: " . $project['ProjectID'] . ", Name: " . $project['Title'] . 
                    ", Start Datum:" . $project['StartDT'] .", Eind Datum:" . $project['EndDT'] .
                    ", Actuel:" . $project['Actual'].", users:" . $project['Name'] .
                    ", Max Hours:" . $project['MaxHours'] .", Description:" .$project['Description'] ."</li>";
                }
                echo "</ul>";
            } else {
                echo "No projects found.";
            }
        } catch (Exception $e) {
            // Handle exceptions (e.g., log or display an error message)
            echo "Error: " . $e->getMessage();
        }
    }
    
    

    private function makeNewProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $sql = "INSERT INTO projects (Title, StartDT, EndDT, Code, Active, Actual, MaxHours) 
                    VALUES (:title, :start_date, :end_date, :code, :active, :actuel, :MaxHours)";
        
            try {
                $stmt = $this->conn->prepare($sql);
        
                // Bind parameters
                $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
                $stmt->bindParam(':start_date', $_POST['start_date'], PDO::PARAM_STR);
                $stmt->bindParam(':end_date', $_POST['end_date'], PDO::PARAM_STR);
                $stmt->bindParam(':code', $_POST['code'], PDO::PARAM_STR);
                $stmt->bindParam(':active', $_POST['active'], PDO::PARAM_STR);
                $stmt->bindParam(':actuel', $_POST['actuel'], PDO::PARAM_STR);
                $stmt->bindParam(':MaxHours', $_POST['MaxHours'], PDO::PARAM_STR);
        
                // Execute the prepared statement
                $stmt->execute();
    
                // Get the ID of the last inserted project
                $projectId = $this->conn->lastInsertId();
    
                // Second SQL query for projectdata table
                $sql = "INSERT INTO projectdata (ProjectID, UserID,EntryDT,WorkDT, Description) 
                        VALUES (:projectId, :userId,:start_date,:end_date ,:description)";
                
                try {
                    $stmt = $this->conn->prepare($sql);
                    // Bind parameters
                    $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
                    $stmt->bindParam(':userId', $_POST['user_id'], PDO::PARAM_INT);
                    $stmt->bindParam(':start_date', $_POST['start_date'], PDO::PARAM_STR);
                    $stmt->bindParam(':end_date', $_POST['end_date'], PDO::PARAM_STR);
                    $stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
        
                    // Execute the prepared statement
                    $stmt->execute();
        
                    echo "New project and project data added successfully!";
                    header("Location: index.php"); // Replace with the actual URL
                    exit();
                } catch (Exception $e) {
                    // Handle exceptions (e.g., log or display an error message)
                    echo "Error: " . $e->getMessage();
                }
            } catch (Exception $e) {
                // Handle exceptions (e.g., log or display an error message)
                echo "Error: " . $e->getMessage();
            }
        }
    }
    
    
    

    private function updateProject($projectId) {
        // Logic to update the project
        // This method should only be accessible by managers
    }

    private function showEmployeeDashboard() {
        // Logic to display employee dashboard or project list
        echo "Employee Dashboard - Own Projects";
        // Assuming $loggedInUserId is the ID of the currently logged-in user
            $loggedInUserId = 2; // Replace with the actual user ID

            $sql = "SELECT 
                projectdata.*, 
                projects.*, 
                users.*, 
                projects.title AS project_name, 
                users.Name AS user_name
            FROM 
                projectdata 
            JOIN 
                projects ON projectdata.ProjectID = projects.ID
            JOIN 
                users ON projectdata.UserID = users.ID
            WHERE 
                projectdata.UserID = :loggedInUserId";

        try {
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':loggedInUserId', $loggedInUserId, PDO::PARAM_INT);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display the projects 
        if ($projects) {
            echo "<ul>";
            foreach ($projects as $project) {
                echo "<li>Project ID: " . $project['ProjectID'] . ", Name: " . $project['Title'] . 
                    ", Start Datum:" . $project['StartDT'] .", Eind Datum:" . $project['EndDT'] .
                    ", Actuel:" . $project['Actual'].", users:" . $project['Name'] .
                    ", Max Hours:" . $project['MaxHours'] .", Description:" .$project['Description'] ."</li>";
            }
            echo "</ul>";
        } else {
            echo "No projects found.";
        }
        } catch (Exception $e) {
        // Handle exceptions (e.g., log or display an error message)
        echo "Error: " . $e->getMessage();
    }

    }

    private function manageTime($projectId) {
        // Logic to manage time for the project
        // This method should only be accessible by employees and managers
    }
}

class ProjectManager{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getProjectsForUser($userId) {
        // Implement logic to retrieve projects based on user ID and access control
        // You can use $this->conn to execute SQL queries
    }

    // Other project management methods...
}

class Authentication {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserInfo($userId) {
        $query = "SELECT users.ID, users.Name, projectusers.MayManage
        FROM users
        JOIN projectusers ON users.ID = projectusers.UserID
        WHERE users.ID = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
