<?php
    $pages = [
        'vtigerwp' => 'settings.view.php',
        'settings' => 'settings.view.php',
    ]
?>
<div id="app-wrapper">

<div class="page-wrapper">

<?php
    if( 
        isset($_GET['page']) && 
        array_key_exists( $_GET['page'], $pages ) 
    ){
        include_once 
            plugin_dir_path( __FILE__ ) 
            . 'pages/' 
            . $pages[ $_GET['page'] ]
        ;
    }
?>

</div>

</div>