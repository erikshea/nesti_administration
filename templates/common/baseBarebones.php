<!DOCTYPE html>
<html lang="fr">

<?php include SiteUtil::toAbsolute("templates/common/head.php") ?>

<body class="<?= $vars["route"]["controller"] . " " . $vars["route"]["controller"] . "__" . $vars["route"]["action"] ?> baseBarebones">
    <main>
        <?php include $vars["actionTemplate"] ?>
    </main>
</body>
</html>