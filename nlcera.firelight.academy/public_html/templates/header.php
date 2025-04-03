<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? escape($page_title) . ' | ' : ''; ?><?php echo escape(SITE_NAME); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>"><?php echo escape(SITE_NAME); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == '/' || strpos($_SERVER['REQUEST_URI'], '/index.php') !== false || strpos($_SERVER['REQUEST_URI'], '/category.php') !== false || strpos($_SERVER['REQUEST_URI'], '/protocol.php') !== false) ? 'active' : ''; ?>" aria-current="page" href="<?php echo BASE_URL; ?>">Protocols</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/tools.php') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/tools.php">Tools</a>
                </li>
                <!-- Add other nav items like Medications if needed -->
                 <li class="nav-item">
                    <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/medications.php') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/category.php?id=9000">Medications</a>
                </li>
            </ul>
            <form class="d-flex" role="search" action="<?php echo BASE_URL; ?>/search.php" method="get">
                <input class="form-control me-2" type="search" name="query" placeholder="Search protocols..." aria-label="Search" required>
                <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <ul class="navbar-nav ms-lg-3">
                 <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/admin/" title="Admin Login"><i class="bi bi-person-circle"></i> Admin</a>
                 </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5 main-content">
    <!-- Page content starts here -->