<?php
move_uploaded_file($_FILES['file']['tmp_name'], "uploaded/".$_FILES['file']['name']);
?>