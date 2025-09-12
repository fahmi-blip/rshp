<?php
require_once "./classes/user.php";

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = new User();
    $user->login($email, $password);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <style>
        .popup {
            width: 100%;
            max-width: 400px;
            background-color: #fff;
            backdrop-filter:blur(20px);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            z-index: 1001;
            padding: 30px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .popup__content {
          width: 100%;
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        
        .popup__header {
          text-align: center;
          font-size: 36px;
          font-weight: bold;
          margin-bottom: 20px;
        }
        
        .containerlogin {
          width: 100%;
        }
        
        input.name[type="text"],
        input[type="password"] {
          width: 100%;
          padding: 12px 15px;
          margin-bottom: 15px;
          border: 1px solid #ccc;
          border-radius: 5px;
          font-size: 16px;
          box-sizing: border-box;
        }
        
        input[type="submit"] {
          width: 100%;
          padding: 12px 15px;
          background-color: #007bff;
          color: white;
          font-size: 16px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          transition: background-color 0.3s ease;
          margin-top: 10px;
        }
        
        input[type="submit"]:hover {
          background-color: #0056b3;
        }
        .signup {
          text-align: center;
          margin-top: 20px;
        }
        
        .signup label {
          color: #007bff;
          cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="popup" id="login-popup">
      <div class="popup__content">
        <div class="containerlogin">
          <h3 class="popup__header">Login</h3>
          <form action="login.php" method="post">
            <input
              class="name"
              type="text"
              placeholder="Masukkan email"
              name="email" required 
            />
            <input type="password" placeholder="Masukkan password" name="password" required />
            <input type="submit" value="Login" class="button" />
          </form>
        </div>
      </div>
      <?php
        if (isset($_SESSION["flash_msg"])) {
          echo "<p style='color: red;'>" . $_SESSION["flash_msg"] . "</p>";
          unset($_SESSION["flash_msg"]);
        }
      ?>
    </div>
</body>
</html>