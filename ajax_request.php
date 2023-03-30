<?php
    include_once("connection.php");

    if (isset($_POST['categoryId'])){

        
        $sql = "SELECT * FROM sub_categories WHERE categoryId={$_POST['categoryId']}";
        $resultCategory = $conn->query($sql);
        if($resultCategory->num_rows > 0){
            $sub_categories = [];    
            while($category = $resultCategory->fetch_assoc()) {
                    //array_merge($category);
                    //echo json_encode($category);
                    $sub_categories[] = $category;
                } 
                echo json_encode($sub_categories);
    }
}               

?>