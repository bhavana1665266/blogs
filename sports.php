<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <!-- <link rel = "stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" type="text/css" href="blog.css">
    <link rel="stylesheet" href="style.css">
    <script src="blog.js"></script>
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
    <div class='message'>
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
            // $sql = "SELECT * FROM post WHERE domain='tech' ";
            $sql = "SELECT * FROM post WHERE title = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $selectedTitle);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="message-box">
                    <div class="message">';
                    echo '<strong>' . $row["title"] . '</strong><br>' . $row["content"];
                    ?>
                     <div>
        <div id="comments" class="comments">
            <div class="comment-form-container">
                <form action="" id="frm-comment" method="post">
                   <label style="font-size:30px;font-weight:extra-bold"><b>Comment Section</b></label>
                    <div class="input-row">
                        <label for="">Enter Name *</label> <br>
                        <input type="hidden" name="comment_id" id="commentId" /> <input class="input-field" type="text" name="name" id="name" required="required" />

                    </div>
                    <div class="input-row">
                        <label for="">Enter Email * </label> <br>
                        <input class="input-field" type="email" name="mail" id="mail" required="required" />

                    </div>
                    <div class="input-row">
                        <label for="">Write your comment below</label>
                        <textarea class="input-field-text" type="text" name="comment" id="comment" required="required"></textarea>
                    </div>
                    <div>
                        <input type="submit" class="btn-submit" id="submitButton" value="Post Comment" />
                    </div>

                </form>

            </div>
            <div id="output"></div>

            <script>
                function postReply(commentId) {
                    $('#commentId').val(commentId);
                    $("#name").focus();
                }

                $("#submitButton").click(function() {
                    $("#comment-message").css('display', 'none');
                    var str = $("#frm-comment").serialize();


                    $.ajax({
                        url: "comments_add-2.php",
                        data: str,
                        type: 'post',

                        success: function(response) {
                            var result = eval('(' + response + ')');
                            if (response) {
                                alert("Reply has been sent !");
                                $("#comment-message").css('display', 'inline-block');
                                $("#name").val("");
                                $("#comment").val("");
                                $("#commentId").val("");
                                $("#mail").val("");
                                listComment();


                            } else {
                                alert("Try Again !");
                                return false;
                            }
                        }
                    });
                });

                $(document).ready(function() {
                    listComment();
                });

                function listComment() {
                    $.post("comments_list-2.php", {

                        },
                        function(data) {
                            var data = JSON.parse(data);

                            var comments = "";
                            var replies = "";
                            var item = "";
                            var parent = -1;
                            var results = new Array();

                            var list = $("<ul class='outer-comment'>");
                            var item = $("<li>").html(comments);

                            for (var i = 0;
                                (i < data.length); i++) {
                                var commentId = data[i]['id'];
                                parent = data[i]['id_reply'];
                                comment_date = data[i]['comment_date'];
                                var newdate = comment_date.split("-").reverse().join("-");


                                if (parent == "0") {
                                    comments =
                                        "<div class='comment-row'>" +
                                        "<div class='comment-info'><span class='commet-row-label'></span> <span class='posted-by'>" + data[i]['name'] + " </span><div> </div><span class='commet-row-label'></span> <span class='posted-at'>" + newdate + "</span></div>" +
                                        "<div class='comment-text'>" + data[i]['comment'] + "</div>" +
                                        "<div><a class='btn-reply' onClick='postReply(" + commentId + ")'>Reply</a></div>" +
                                        "</div>"

                                    var item = $("<li>").html(comments);
                                    list.append(item);
                                    var reply_list = $('<ul>');
                                    item.append(reply_list);
                                    listReplies(commentId, data, reply_list);
                                }
                            }
                            $("#output").html(list);
                        });
                }

                function listReplies(commentId, data, list) {
                    for (var i = 0;
                        (i < data.length); i++) {
                        comment_date = data[i]['comment_date'];
                        var newdate = comment_date.split("-").reverse().join("-");
                        if (commentId == data[i].id_reply) {

                            var comments =
                                "<div class='comment-row'>" +
                                "<div class='comment-info'><span class='commet-row-label'></span> <span class='posted-by'>" + data[i]['name'] + " </span> <span class='commet-row-label'></span><div></div> <span class='posted-at'>" + newdate + "</span></div>" +
                                "<div class='comment-text'>" + data[i]['comment'] + "</div>" +
                                "<div style='display:none'><a class='btn-reply' onClick='postReply(" + data[i]['id'] + ")'>Cevapla</a></div>" +

                                "</div>";
                            var item = $("<li>").html(comments);
                            var reply_list = $('<ul>');
                            list.append(item);
                            item.append(reply_list);
                            listReplies(data[i].id, data, reply_list);
                        }
                    }
                }
            </script>

        </div>
    </div>
    </div>
  <?php
                    echo '</div>';
                    echo '</div>';
                    // echo '<li><strong>' . $row["title"] . '</strong><br>' . $row["content"] . '</li>';
                }
            } else {
                echo "No blog post found with the selected title.";
            }
        } else {
            // If no title is selected, list all available titles
            $sql = "SELECT title FROM post WHERE domain='sports'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // echo '<a href="?title=' . urlencode($row["title"]) . '">style="text-decoration: none;"' . $row["title"] . '</a>';
                    echo '<div class="message-box">
                    <div class="message">';
                    echo '<a href="?title=' . urlencode($row["title"]) . '"style="text-decoration: none;">' . $row["title"] . '</a>';
                    echo '</div></div>';
                }
            } else {
                echo "No blog posts found in the database.";
            }
        }

        // Close the database connection
        $conn->close();
        ?>
    <!-- </ul>
    </div>
    </div> -->
</body>
</html>




