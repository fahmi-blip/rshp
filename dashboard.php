<?php
    session_start();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
       include("menu.php");
    ?>
    <main style="text-align: center;">
        <h1>Halo, <?php echo $_SESSION['user']['nama'];?></h1>
    </main>
</body>
</html>