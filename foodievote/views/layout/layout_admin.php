<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'FoodieVote Admin'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    require_once __DIR__ . '/../partials/navbar_admin.php';
    ?>

   

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php
                        // This is where the actual page content will be included
                        if (isset($contentViewPath) && file_exists($contentViewPath)) {
                            require_once $contentViewPath;
                        } else {
                            // Fallback or error message if content view is not set or not found
                            echo "<h1>Content Not Found</h1><p>The requested content could not be displayed.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/alterhandler.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/prefImg.js"></script>
</body>
</html>