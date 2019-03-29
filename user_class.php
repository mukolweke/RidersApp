<?php

require_once 'dbconfig.php';

class USER
{
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	//function for the registration of the users
	public function register($fname,$lname,$email,$phone,$pass,$code)
	{
		try
		{							
			$password = md5($pass);
			$stmt = $this->conn->prepare("INSERT INTO users_table(fname,lname,email,phone,password,tokenCode) 
			                                             VALUES(:fname, :lname, :email, :phone, :password, :tokenCode)");
			$stmt->bindparam(":fname",$fname);
			$stmt->bindparam(":lname",$lname);
			$stmt->bindparam(":email",$email);
			$stmt->bindparam(":phone",$phone);
			$stmt->bindparam(":password",$password);
			$stmt->bindparam(":tokenCode",$code);

			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	//function for user login
	public function login($email,$pass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM users_table WHERE email=:email");
			$stmt->execute(array(":email"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				if($userRow['userStatus']=="Y")
				{
					if($userRow['password']==md5($pass))
					{
						$_SESSION['userSession'] = $userRow['userID'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}
	
	//function for sending mail
	function send_mail($email,$message,$subject)
	{						
		require_once('mail-setup/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="yakjunior78@gmail.com";  
		$mail->Password="3November2013";            
		$mail->SetFrom('yakjunior78@gmail.com','Blue Limo');
		$mail->AddReplyTo("yakjunior78@gmail.com","Blue Limo");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}	

	//function for creating a ride
	function give_ride($name,$did,$origin,$destination,$capacity)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO riders_table(driver_name, driver_id, origin, destination, capacity) VALUES(:driver_name, :driver_id, :origin, :destination, :capacity)");
			
			$stmt -> bindParam(':driver_name', $name);
			$stmt -> bindParam(':driver_id', $did);
			$stmt -> bindParam(':origin', $origin);
			$stmt -> bindParam(':destination', $destination);
			$stmt -> bindParam(':capacity', $capacity);

			$stmt->execute();
			return $stmt;

		}
		catch(SQLException $er)
		{
			echo $eR->getMessage();
		}
	}

	//function for reserving a ride
	function reserve_ride($rideid,$driverid,$drivername,$riderid,$origin,$destination,$email)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO reserved_rides(ride_id, driver_id, driver_name, rider_id, origin, destination, rider_email) VALUES(:ride_id, :driver_id, :driver_name, :rider_id, :origin, :destination, :rider_email)");
			
			$stmt -> bindParam(':ride_id', $rideid);
			$stmt -> bindParam(':driver_id', $driverid);
			$stmt -> bindParam(':rider_id', $riderid);
			$stmt -> bindParam(':driver_name', $drivername);
			$stmt -> bindParam(':origin', $origin);
			$stmt -> bindParam(':destination', $destination);
			$stmt -> bindParam(':rider_email', $email);

			$stmt->execute();
			return $stmt;
		}
		catch(SQLException $er)
		{
			echo $eR->getMessage();
		}
	}
}