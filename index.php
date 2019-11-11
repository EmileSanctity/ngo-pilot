<?php


?>
<!DOCTYPE>
<html>
<head>
	<link rel="shortcut icon" href="include/icons/logo.png" />
	<link rel="shortcut icon" href="include/icons/logo.png" />
    <link rel="stylesheet" href="vmsstyle.css">
	<title>VMS</title>
</head>
<body>
    <div style="text-align:center;width:600px;margin:0 auto;">
    	<img src="include/icons/logo.jpg" width="400px" height="200px"/>
    	<br>
    	<h3>Personnel Management System</h3>
    </div>
    <div style="text-align:center;width:600px;margin:0 auto;">
    	
		<h4 style="font-size:20px">Please enter in your login details</h4>
    	<form method="POST" action="logincheck.php">
			<table style="margin:0 auto;border:0px;">
				<tr>
					<td colspan="2">
					<?php
						if(isset($_GET['message'])){
							echo('<span style="color:red;">'.$_GET['message'].'</span>');
						}
                    ?>
					</td>
				</tr>
				<tr>
					<td style="text-align:right;">Email</td>
					<td>
						<input style="background-color:#e6e6e6;" type="text" name="email" placeholder="Email Address" />
					</td>
				</tr>
				<tr>
					<td style="text-align:right;">Password</td>
					<td>
						<input style="background-color:#e6e6e6;" type="password" name="password" placeholder="Password" />
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<input type="submit" value="Submit" />
					</td>
				</tr>
                <tr>
                    <td colspan="2" style="text-align:right;"><a href="forgotpasswd.php" >Forgot password</a></td>
                </tr>
			</table>
    	</form>
    </div>
</body>
</html>