<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/css/bootstrap.min.css" ?>'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/css/main.css" ?>'>
    <?php foreach ($vars['assets']['css']  as $stylesheetName): ?>
        <link rel='stylesheet' type='text/css' media='screen' href='<?= "{$vars['assetsUrl']}/css/$stylesheetName.css?1" ?>'>
    <?php endforeach; ?>
    
    <script src='<?= "{$vars['assetsUrl']}/js/jquery-3.5.1.min.js" ?>'></script>
    <script src='<?= "{$vars['assetsUrl']}/js/bootstrap.min.js" ?>'></script>
    <script src='<?= "{$vars['assetsUrl']}/js/main.js" ?>'></script>
    <?php foreach ($vars['assets']['js']  as $scriptName): ?>
        <script src='<?= "{$vars['assetsUrl']}/js/$scriptName.js" ?>'></script>
    <?php endforeach; ?>

</head>
