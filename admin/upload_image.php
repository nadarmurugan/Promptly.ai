<?php
if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid().'.'.$ext;
    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/'.$filename);
    echo $filename;
}
?>
