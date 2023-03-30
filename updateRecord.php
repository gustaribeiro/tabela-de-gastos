<?php
    include 'connection.php';
  /*   var_dump($_POST);
    die; */
    
    if (isset($_POST['financeId'])) {
        $financeId = $_POST['financeId'];
        $categoryId = $_POST['categoryId'];
        $subCategoryId = $_POST['subCategoryId'];
        $monthId = $_POST['monthId'];
        $value = $_POST['value'];

        $sql = "UPDATE finances SET value='$value', monthId='$monthId' WHERE financeId='$financeId' and categoryId='$categoryId' and sub_categoryId='$subCategoryId'";
        var_dump($sql);
        //die;


        if ($conn->query($sql) === TRUE) {
          
            echo 'testando';
            exit;            
        } else {
            echo 'Erro ao atualizar registro financeiro: ' . $conn->error;
            exit;
        }
    }