<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'FoodieVote Guest'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URL; ?>/assets/images/foodievote-logo1.png">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/about.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <?php require_once __DIR__.'/../partials/navbar_guest.php'; ?>

    <!-- <div class="container mt-5">
        <div class="row"> -->
            <?php
            // This is where the actual page content will be included
            if (isset($contentViewPath) && file_exists($contentViewPath)) {
                require_once $contentViewPath;
            } else {
                // Fallback or error message if content view is not set or not found
                echo '<h1>Content Not Found</h1><p>The requested content could not be displayed.</p>';
            }
    ?>
        </div>
    </div>

    <?php require_once __DIR__.'/../partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/about.js"></script>
</body>
</html>
