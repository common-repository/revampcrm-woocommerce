
<?php 
if(isset($_GET['error_notice'])) {  
    switch($_GET['error_notice']){ 
        case "missing_api_key":
            include(__DIR__.'/errors/missing_api_key.php');
            break;
        default:
            break;
    }    
} 

if(isset($_GET['success_notice'])) {  
    switch($_GET['success_notice']){ 
        default:
            break;
    }    
} 
?>

