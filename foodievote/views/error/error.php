<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>404 - Nyasar Bang</title>
   <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/error.css">
   <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>/assets/images/foodievote-logo1.png">
</head>
<body>

<div class="error-box">

    
    <img class="robot-img" src="<?php echo BASE_URL; ?>/assets/images/robot-grocery.png" title="" alt="Robot Kocak Bawa Troli">

    <h1>404</h1>
    <h2>Wah, Kamu Kelewat Jauh!</h2>
    <p>
        URL ini kayanya belum sempet dibuat:
        <br>
        <code><?php echo htmlspecialchars($_GET['page'] ?? '—'); ?></code>
    </p>
    <p>
        Robot FoodieVote udah ceki-ceki, tapi ternyata kosong. 
        Sepertinya kamu nyasar… atau lapar???
    </p>

    <a class="button" href="index.php?page=home">Balik ke Home Aja Yuk</a>
</div>

</body>
</html>
