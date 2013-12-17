<?php
$base_path = getcwd() . '/application/views/admin/';
require( $base_path . 'div/header.php' ); ?>

<body>
<?php
require( $base_path . 'div/nav.php' ); 
?>

<?php 
switch( $page_name ) {
    case 'applied':
        require( $base_path . 'div/applied_list.php' ); 
        break;
    case 'applying':
        require( $base_path . 'div/applying_list.php' ); 
        break;
} 
?>
</body>

<?php require( $base_path . 'div/footer.php' ); ?>

