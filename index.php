<?php
	session_start();

	$conn = new mysqli("localhost","root","","cp");
	$msg = "";

	if (isset($_post['login'])) {
		$username = $_post['username'];
		$password = $_post['password'];
		$password = sha1($password);
		$userType = $_post['userType'];

		$sql = "SELECT * FROM users WHERE username=? AND password=? AND user_type=?";
		$stmt= $conn->prepare($sql);
		$stmt->bind_param("sss",$username,$password,$userType);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		session_regenerate_id();
		$_SESSION['username'] = $row['username'];
		$_SESSION['role'] = $row['user_type'];
		session_write_close();

		if ($result->num_rows==1&&$_SESSION['role']=="admin") {
			header("location:admin.php");
		}
		elseif ($result->num_rows==1&&$_SESSION['role']=="user") {
			header("location:user.php");
		}
		else{
			$msg = "Username or Password is Incorrect!!!";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="style.css">	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="box">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-5 bg-light mt-5 px-0">
				<h3 class="text-center text-light bg-danger p-3">Login Here</h3>

				<form action="<?= $SERVER['PHP_SELF']?>" method="post" class="p-4">
					<div class="form-group">
						<input type="text" name="username" class="form-control form-control-lg" placeholder="username" required="">
						
					</div>
					<div class="form-group">
						<input type="pasword" name="pasword" class="form-control form-control-lg" placeholder="pasword" required>
						
					</div>
					<div class="form-group lead">
						<label for="userType"></label>
					<input type="radio" name="userType" value="admin" class="custom-radio" required>&nbsp;<b>Admin</b> |
					<input type="radio" name="userType" value="user" class="custom-radio" required>&nbsp;<b>User</b>
						
					</div>
					<div class="form-group">
						<input type="submit" name="login" value="submit" class="btn btn-danger btn-block">
					</div>
					<h5 class="text-center text-danger"><?=$msg;?></h5>
				</form>
				
			</div>
			
		</div>
		
	</div>

</body>
</html>