<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title><?= $vars["title"] ?? "Administration Nesti" ?></title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/fontawesome/css/all.css" ?>'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/css/bootstrap.min.css" ?>'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/css/main.css?version={$vars['version']}" ?>'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/css/mainNav.css?version={$vars['version']}" ?>'>
    <?php foreach ($vars['assets']['css']  as $stylesheetName): ?>
        <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/css/$stylesheetName.css?version={$vars['version']}" ?>'>
    <?php endforeach; ?>
    
    <script src='<?= "{$vars['assetsUrl']}/js/jquery-3.5.1.min.js" ?>'></script>
    <script src='<?= "{$vars['assetsUrl']}/js/bootstrap.min.js" ?>'></script>
    <script src='<?= "{$vars['assetsUrl']}/js/main.js?version={$vars['version']}" ?>'></script>
    <?php foreach ($vars['assets']['js']  as $scriptName): ?>
        <script src='<?= "{$vars['assetsUrl']}/js/$scriptName.js?version={$vars['version']}" ?>'></script>
    <?php endforeach; ?>

</head>
