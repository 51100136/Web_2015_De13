<?php 
session_start();
$error = '';
if (isset($_POST['submitted']) or isset($_POST['login'])){
	// Define 
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'ql_user');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');

	// Connect
	$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Failed to connect to MySQL" . mysql_error());
	$db = mysql_select_db(DB_NAME, $con) or die("Failed to connect to MySQL" . mysql_error());

	// 
	$ID = $_POST['username'];
	$Password = $_POST['password'];

	// SignIn Function
	function SignIn() {
		

		if (!empty($_POST['username'])) {

			$query = mysql_query("SELECT * FROM user_detail where username = '$_POST[username]' and pass = '$_POST[password]'");

			if (false === $query) {
				echo mysql_error();
			}

			$row = mysql_fetch_array($query);

			if (!empty($row['username']) and !empty($row['pass'])) {
				$_SESSION['statusLogin'] = 'YES';
				$_SESSION['username'] = $_POST['username'];
				header("location: skybox.php");
			}
			else {
				echo "SORRY ... WRONG USERNAME OR PASSWORD!";
				$error = "Wrong username or pasword";
			}
		}
	}

	// NewUser Function
	function NewUser() {
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$gender = $_POST['gender'];
		$country = $_POST['country'];

		// Insert
		$query = mysql_query("INSERT INTO user_detail (username, pass, firstname, lastname, email, gender, country) VALUES ('$username', '$password', '$firstname', '$lastname', '$email', '$gender', '$country')") or die("Failed to insert to database");

		if ($query) {
			header("location: skybox.php");
		}
	}

	// SignUp Function
	function SignUp() {
		if (!empty($_POST['username'])) {
			$query = mysql_query("SELECT * FROM user_detail WHERE username = '$_POST[username]' and pass = '$_POST[password]'");
			$row = mysql_fetch_array($query);

			if (!$row) {
				NewUser();
			}
			else {
				echo "YOU ARE ALREADY REGISTERED!";
			}
		}
	}

	if (isset($_POST['login'])) {
		SignIn();
	}

	if (isset($_POST['submitted'])) {
		SignUp();
	}

	mysql_close($con);
}

?>
