<!DOCTYPE html>
<html>
<head>
    <title>My Note App</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>

<?php



// Establish database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'notes';


$conn = mysqli_connect($host, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sql = "create table notes (
    id int(11) unsigned auto_increment primary key,
    title varchar(255) not null,
    content text not null,
    created_at datetime default null,
    header_image varchar(255) default null
)";

if (mysqli_query($conn, $sql)) {
    echo "Table notes created successfully";

} else {
    echo "Error creating table: " . mysqli_error($conn);
}

// Close the database connection


// ...

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $created_at = date('Y-m-d H:i:s');
    
 
    
    $headerImage = $_FILES['header_image'];
    $headerImagePath = '';


    if ($headerImage['error'] === 0) {
        $uploadDir = 'uploads/'; 
        $headerImageName = $headerImage['name'];
        $headerImagePath = $uploadDir . $headerImageName;
        move_uploaded_file($headerImage['tmp_name'], $headerImagePath);
    }
    $sql = "INSERT INTO notes (title, content, created_at, header_image) VALUES ('$title', '$content', '$created_at', '$headerImagePath')";
    if (mysqli_query($conn, $sql)) {
        echo "Note created successfully";
    } else {
        echo "Error creating note: " . mysqli_error($conn);
    }
}

// ...
?>


    <div class="container">
        <h1>My Note App</h1>
        <form action="index.php" method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Enter note title" required>
    <textarea name="content" placeholder="Enter note content" required></textarea>
    <input type="file" name="header_image" accept="image/*">
    <button type="submit">Save Note</button>
</form>
        <h2>All Notes</h2>
        <div class="notes-container">
 <?php
$sql = "SELECT * FROM notes ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $noteTitle = $row['title'];
        $noteContent = $row['content'];
        $headerImagePath = $row['header_image'];

        echo '<div class="note">';
        echo '<h3>' . $noteTitle . '</h3>';
       
        echo '<p>' . $headerImagePath . '</p>';
        echo '</div>';
    }
} else {
    echo 'No notes found.';
}


?> 


        </div>
    </div>
</body>
</html>
