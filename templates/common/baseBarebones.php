<!DOCTYPE html>
<html>

<?php include SiteUtil::toAbsolute("templates/common/head.php") ?>

<body class="<?= $vars["route"]["controller"] . " " . $vars["route"]["controller"] . "-" . $vars["route"]["action"] ?>">
    <main>
        <?php include $vars["actionTemplate"] ?>
    </main>

</body>
</html>