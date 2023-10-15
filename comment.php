<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="TR" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
    <link rel="stylesheet" href="style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>

<body>
<!-- <nav class="navtop" style="background-color:#008080;">
         <div>
            <h1 style="color:#FFFF00;font-size:30px;font-family:sans;">
            Comment System</h1>
         </div>   
    </nav>
    <div class="content home">
        <h2>Article Lorem </h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque magni sapiente,
             animi unde consectetur repellendus tenetur corrupti, numquam amet, cumque et. Quo 
             fugiat non amet repellat eaque aperiam aliquam delectus! Laudantium ipsum consectetur 
             ratione voluptas voluptate minima perspiciatis qui libero eaque quos.
             Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque magni sapiente,
             animi unde consectetur repellendus tenetur corrupti, numquam amet, cumque et. Quo 
             fugiat non amet repellat eaque aperiam aliquam delectus! Laudantium ipsum consectetur 
             ratione voluptas voluptate minima perspiciatis qui libero eaque quos.
            </p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque magni sapiente,
                animi unde consectetur repellendus tenetur corrupti, numquam amet, cumque et. Quo 
                fugiat non amet repellat eaque aperiam aliquam delectus! Laudantium ipsum consectetur 
                ratione voluptas voluptate minima perspiciatis qui libero eaque quos.
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque magni sapiente,
                animi unde consectetur repellendus tenetur corrupti, numquam amet, cumque et. Quo 
                fugiat non amet repellat eaque aperiam aliquam delectus! Laudantium ipsum consectetur 
                ratione voluptas voluptate minima perspiciatis qui libero eaque quos.
               </p>
</div> -->
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
        }
            ?>
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
                        url: "comments_add.php",
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
                    $.post("comments_list.php", {

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
</body>

</html>