<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="TR" /> -->
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <!-- <link rel = "stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" href="blog.css">
    <!-- <link rel="stylesheet" href="style.css">
    <script src="blog.js"></script> -->
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
    <link rel="stylesheet" href="style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <title>BLOGS</title>
</head>
<body>
    <div id="background-image"></div>
        <header>
            <h1>DISCOVER BLOGS</h1>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="blog.php">Blogs</a></li>
                    <li><a href="post.html">Post</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </nav>
        </header>
    </div>
    <div class="button-container">
        <a href="/blogs/blog.php"><button>ALL</button></a>
        <a href="/blogs/tech.php"><button>TECHNOLOGY</button></a>
        <a href="/blogs/health.php"><button>HEALTH</button></a>
        <a href="/blogs/sports.php"><button>SPORTS</button></a>
    </div>
    <!-- <div class='message-box'>
    <div class='message'> -->
    <!-- <div class='view'>
    <ul> -->
        <?php
        // Database connection settings
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "blog";

        // Create a connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if a title is selected
        if (isset($_GET['title'])) {
            $selectedTitle = $_GET['title'];

            // Query to select the blog post with the specified title
            $sql = "SELECT * FROM post WHERE title = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $selectedTitle);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="message-box">
                    <div class="message">';
                    // <div class="title-box">
                    echo '<strong>' . $row["title"] . '</strong><br>' . $row["content"] ;
                    
                    // echo"<br>"<li>. '</li>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No blog post found with the selected title.";
            }
        } else {
            // If no title is selected, list all available titles
            $sql = "SELECT title FROM post";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    
                    echo '<div class="message-box">
                    <div class="message">';
                    echo '<a href="?title=' . urlencode($row["title"]) . '"style="text-decoration: none;">' . $row["title"] . '</a>';
                    
                    echo '</div></div>';
                }
            } else {
                echo "No blog posts found in the database.";
            }
        }
        
        $conn->close();
        ?>
    <!-- </ul> -->
    <!-- </div>
    </div> -->
</body>
</html>
