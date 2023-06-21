<?php
    include 'connection.php';
  /*   var_dump($_POST);
    die; */
    
    if (isset($_POST['financeId'])) {
        $financeId = $_POST['financeId'];

        $sql = "DELETE FROM finances WHERE financeId='$financeId'";
        //var_dump($sql);
        //die;


        if ($conn->query($sql) === TRUE) {
          
            echo json_encode(['success'=>true]);
            exit;            
        } else {
            echo 'Erro' . $conn->error;
            exit;
        }
    }
