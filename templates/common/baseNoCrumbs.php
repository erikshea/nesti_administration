<!DOCTYPE html>
<html>

<?php include SiteUtil::toAbsolute("templates/common/head.php") ?>

<body class="<?= $vars["route"]["controller"] . " " . $vars["route"]["controller"] . "__" . $vars["route"]["action"] ?>">
    <?php include SiteUtil::toAbsolute("templates/common/mainNav.php") ?>
    <main>
        <?php include SiteUtil::toAbsolute("templates/common/mainTitle.php") ?>
        <?php include $vars["actionTemplate"] ?>
    </main>
</body>
</html>