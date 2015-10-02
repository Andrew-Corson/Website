<?php
	if($_SERVER['REQUEST_METHOD'] != 'POST')
		header('Location: index.php');
	$username = $_POST['username'];
	$password = $_POST['password'];
	$r = $_POST['request'];
?>

<?php
	function retry() {
		echo <<< end
		<p>Retry login here:</p>
			
			<form method="POST" action="">
				<input type = "username" name = "username" placeholder="Username" />
				<input type="password" name = "password" placeholder="Password" />
				<input type="hidden" name="request" value="default" />
				<input type="submit" value="Login" />
			</form>
			
			<a href="index.php">or return to the home page</a>
end;
	}
?>

<!DOCTYPE html>

<html>

	<head>
		<title>Staff Only!</title>
	</head>
	
	<body>
		<!--This php will stop the page from loading if the username doesn't exist or if they 
		used the wrong password -->
		<?php
			
			try{
				$connect = new PDO('mysql:host=striationsappcom.ipagemysql.com;dbname=striations', 'striations', 'striations');
				$connect ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				$query = "SELECT * FROM users WHERE username=:usr;";
				$user = $connect->prepare($query);
				$user->bindParam(':usr', $username);
				$user->execute();
				
				if($user->rowCount() == 0) {
					echo "<p>User not found!</p>";
					retry();
					die();
				}
				
				$staff = $connect->prepare("SELECT * FROM users WHERE username=:usr AND password=:pswr;");
				$staff->bindParam(':usr', $username);
				$staff->bindParam(':pswr', $password);
				$staff->execute();
				if($staff->rowCount() == 0) {
					echo "<p>Password Incorrect</p>";
					retry();
					die();
				}
				
				
			} catch (PDOException $e) {
				die('mySQL connection error ' . $e->getMessage());
			}
?>
	<!-- When page access is granted, this is the default gui that will always be seen -->
	<?php
		if($r == "emails")
			$r = "default";
		else if($r == "default")
			$r = "emails";
	
	?>
	<form method="POST" action="">
		<input type="hidden" name="request" value="<?php echo $r; ?>" />
		<input type="hidden" name="username" value="<?php echo $username; ?>" />
		<input type="hidden" name="password" value="<?php echo $password; ?>" />

		<input type="submit" value='<?php
		if($r == "default")
			echo "Hide emails";
		else if($r == "emails")
			echo "See the email list";
		?>'
		/>
	</form>
	
	<?php
	//tests for 'default' instead of 'emails' because the string was already switched above
	if($r == "default") {
		$query = "SELECT emails FROM emails;";
		$emails = $connect->query($query);
		
		while($e = $emails->fetchColumn())
			echo $e . "<br />";
	}
	?>
	<br />
	<br />
	
	<a href="index.php">Return to home page</a>
	


	</body>

</html>