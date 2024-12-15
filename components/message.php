<?php

if(isset($success_msg)){
    foreach($success_msg as $success_msg){
        echo '<script>swal("'.$success_msg.'","","success");</script>';
    }
}

if(isset($warnning_msg)){
    foreach($warnning_msg as $warnning_msg){
        echo '<script>swal("'.$warnning_msg.'","","warnning");</script>';
    }
}

if(isset($info_msg)){
    foreach($info_msg as $info_msg){
        echo '<script>swal("'.$info_msg.'","","info");</script>';
    }
}

if(isset($error_msg)){
    foreach($error_msg as $error_msg){
        echo '<script>swal("'.$error_msg.'","","error");</script>';
    }
}

?>

