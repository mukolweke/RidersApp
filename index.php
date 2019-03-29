<?php
session_start();
require_once 'user_class.php';

$user_login = new USER();

if($user_login->is_logged_in() != "")
{
    $user_login->redirect('user-account.php');
}

if(isset($_POST['login-button']))
{
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);
    
    if($user_login->login($email,$pass))
    {
        $user_login->redirect('user-account.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>

	<title>Shareride | Sign in</title>

	<link rel="stylesheet" href="assets/css/demo.css">
	<link rel="stylesheet" href="assets/css/style.css">

</head>

	<header>
        <h1>:: Shareride Inc.</h1>
        
    </header>
    <div class="main-menu">
        <div class="col-md-6 left-menu">
            <ul>
                <li><a href="index.html" class="active">Welcome</a></li>
            </ul>
        </div>
        <div class="col-md-6 right-menu">
            <ul>
                <li>You don't have an account? <a href="user-signup.php" class="form-facebook-button">Sign up</a></li>
            </ul>
        </div>

    </div>


    <div class="main-content">

        <div class="form-register">
            <div class="col-md-8">
                <div class="right-section">
                    <div class="form-row form-title-row">
                        <span class="form-title">Welcome to shareride inc.</span>
                    </div>
                    <div class="form-row">
                        <div class="home-sec">
                            <h2 class="rider-header">Find a ride</h2>
                            <p class="rider-parag">Review, reserve and get a ride that matches your desired route.</p>
                        </div>
                        <div class="home-sec">
                            <h2 class="rider-header">Give a ride</h2>
                            <p class="rider-parag">Indicate your route, let riders find you and give them a ride.</p>
                        </div>
                        
                        <div class="form-row-button">
                            <a href="" class="form-row-button">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">

                <form class="" method="post" action="#">

                    <div class="left-section">

                        <div class="form-white-background">


                            <div class="form-title-row">
                                <h1>Login to your account</h1>
                            </div>
                                    
                            <div class="form-title-row">
                                <div class="error-section">
                                    <?php 
                                      if(isset($_GET['inactive']))
                                      {
                                        ?>
                                        <div class='alert alert-error'>
                                          <strong>Sorry!</strong> This Account is not Activated,Check email to activate 
                                        </div>
                                    <?php
                                      }
                                    ?>

                                    <?php
                                    if(isset($_GET['error']))
                                      {
                                    ?>
                                        <div class='alert alert-success'>
                                          <strong>Wrong Details!</strong> 
                                        </div>
                                    <?php
                                      }
                                    ?>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>
                                    <span>Email</span>
                                    <input type="email" name="email">
                                </label>
                            </div>

                            <div class="form-row">
                                <label>
                                    <span>Password</span>
                                    <input type="password" name="password">
                                </label>
                            </div>

                            <div class="form-row">
                                <button type="submit" name="login-button">Login</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
                

    </div>

</body>

</html>
