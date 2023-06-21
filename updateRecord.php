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

        $sql = "UPDATE finances SET value='$value'  WHERE financeId='$financeId'";
        //var_dump($sql);
        //die;


        if ($conn->query($sql) === TRUE) {
            //ao trabalhar com ajax sempre usar o json_encode
            echo json_encode(['success'=>true]);
            exit;            
        } else {
            echo 'Erro ao atualizar registro financeiro: ' . $conn->error;
            exit;
        }
    }
