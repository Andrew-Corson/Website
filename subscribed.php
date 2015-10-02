<?php
	$host = 'striationsappcom.ipagemysql.com';
	$username = 'striations';
	$password = 'striations';
	$dbname = 'striations';
	
	if($_SERVER['REQUEST_METHOD'] != 'POST')
		header('Location: index.php');
	
	try {
		$connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
		$connection ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$email = $_POST['email'];
		$query = "SELECT * FROM emails WHERE emails=:eml;";
		$result = $connection->prepare($query);
		$result->bindParam(':eml', $email);
			
		$result->execute();
		
		if($result->rowCount() > 0)
			$message = "We already have your email!";
		else {
			$message = "Thanks for subscribing! A confirmation email was sent to {$email}";
			
			$add = "INSERT INTO emails (emails) VALUES (:eml);";
			$prep = $connection->prepare($add);
			$prep -> bindParam(':eml', $email);
			$prep->execute();
			
			mail($email, "Striations Email Confirmation",
			<<< end
			Thanks for subscribing to our email update service! 
			Don't worry, we'll keep these emails short and will 
			only send them for major updates that we think you
			should know about.
end
			, "From: 'Striations Email Confirmation' <noreply@striationsapp.com>"."\r\n".
			"Reply-To: feedback@striationsapp.com");
		}
	}catch (PDOException $e) {
		echo "Couldnt connect to mysql database! " . $e->getMessage();
	}
?>

<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<title>Striations</title>

<link rel="stylesheet" href="style.css">

<style type="text/css">
/*
This was coded by João Sardinha
Visit me at http://johnsardine.com/

It has been tested in all modern browsers.

This coming soon page design has been created by the awesome Orman Clark,
you can download a PSD version of this at his website, PremiumPixels.
http://www.premiumpixels.com/freebies/coming-soon-splash-page-psd/
*/
</style>

</head>

<body>

<div id="coming-soon">

		<h3><?php echo $message; ?></h3>
		<br />
		<h3><a href="index.php">Return to home page</a></h3>

</div>

</body>
</html>