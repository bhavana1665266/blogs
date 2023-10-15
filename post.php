<?php
		$conn = mysqli_connect("localhost", "root", "", "blog");
		$name = $_POST['name'];
    $email = $_POST['email'];
    $title = $_POST["title"];
    $domain = $_POST["domain"];
    $contents = $_POST["content"];
		// Check connection
		if($conn === false){
			die("ERROR: Could not connect. "
				. mysqli_connect_error());
		}
		$content = $conn->real_escape_string($contents);
		$sql = "INSERT INTO post VALUES ('$name','$email','$title','$domain','$content')";
    // $stmt = $conn->prepare('insert into post(name,email,,domain,sect,cgpa,cid) values(?,?,?,?,?,?,?)');
    // $stmt->bind_param("issssdi",$id,$rollnum,$Sname,$dept,$sect,$cgpa,$cid);
		
		if(mysqli_query($conn, $sql)){
      echo"<link rel='stylesheet' type='text/css' href='post.css'>";
			// echo "<h3 style='font-size: 50px'>data stored in a database successfully.</h3><br><br>";
      // echo "<a href='post.html' style=>POST</a>";
      echo "<div id='background-image'></div>
      <header>
          <h1>DISCOVER BLOGS</h1>
          <nav>
              <ul>
                  <li><a href='index.html'>Home</a></li>
                  <li><a href='blog.php'>Blogs</a></li>
                  <li><a href='post.html'>Post</a></li>
                  <li><a href='contact.html'>Contact</a></li>
              </ul>
          </nav>
      </header>
      <main><h2 style='font-size: 70px;margin-bottom: 20px;text-align:center'>YOUR<br> BLOG<br> IS<br> SUBMITTED</h2></main>";
		} else{
			echo "ERROR: Hush! Sorry $sql. "
				. mysqli_error($conn);
		}
		

		// Close connection
		mysqli_close($conn);
		?>