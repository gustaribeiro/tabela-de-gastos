<?php
    include_once("connection.php");
 
  //session_start();
  if(!$conn){
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
  }

  $sql = 'SELECT * FROM categories';
  $result = $conn->query($sql);
  

  // REMOVIDO PARTE QUE SALVAVA

  
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>  
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
    <title>Tabela de Gastos</title>
    <link rel="stylesheet" href="./css/tabela.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">

</head>
<body>
  <!--NAVBAR DA PÁGINA-->
    <nav class="navbar navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="image/icon_porco_24dp.svg" alt="logo">
          Tabela de Gastos
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
            <a class="nav-link" href="login.php">Login</a>
            <a class="nav-link" href="cadastro.php">Cadastro</a>
          </div>
        </div>
      </div>
    </nav>

    


    <!--FORMULÁRIO DE GASTOS-->
    <div id="formulario" class="container col-sm-4 red">
      <form id="formularioDeCadastro" action="" method="POST">
        <label for="area">Selecione a Categoria:</label>
        <select id="category" name="category" class="form-select" aria-label="Default select example">
          <?php
                  
            if($result->num_rows > 0){
              while($category = $result->fetch_assoc()) {
                echo '<option value="'.($category['categoryId']).'">'.$category['name'].'</option>';              }
            } 
           
          ?>
        </select>
        <br>

        <label for="despesa">Escolha a Subcategoria:</label>
        <select name="subCategory" id="subCategory" class="form-select" aria-label="Default select example">
            <?php
               /*  $sql = 'SELECT * FROM sub_categories';
                $resultCategory = $conn->query($sql);
              if($resultCategory->num_rows > 0){
                while($category = $resultCategory->fetch_assoc()) {
                  echo '<option value="'.($category['sub_categoryId']).'">'.$category['name'].'</option>';             
                }
              }  */
                        
            
            ?>
        </select>
        <br>

        <label for="">Escolha o Mês:</label>
        <select class="form-select" name="month" id="month" aria-label="Default select example">
            <?php
              $sql = 'SELECT * FROM months';
              $resultMonth = $conn->query($sql);
            if($resultMonth->num_rows > 0){
              while($month = $resultMonth->fetch_assoc()) {
                echo '<option value="'.($month['monthId']).'">'.$month['name'].'</option>';              
              }
            } 
          ?>
          </select>
         
        <br>
        <label for="">Digite o Valor:</label> 
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">R$</span>
          </div>
          <input name="value" id="value" type="number_format" class="form-control" aria-label="valor">
        </div>
        <br>
        <input type="hidden" name="userId" value="<?=$_SESSION['userId'] ?? 1?>">

        <input type="hidden" name="insertFinance" value="insertFinance">


        <input type="submit" id="submitButtonFinance" name="submitButtonFinance" class="btn btn-outline-dark mx-auto d-flex justify-content-center" value="Enviar">
        
      </form>
    </div>

    <br><br>
        <!--TABELA DE GASTOS-->
        <?php

           $monthNames = array(1 => "Janeiro", 2 => "Fevereiro", 3 =>"Março", 4 =>"Abril", 5 => "Maio", 6 => "Junho", 7 =>"Julho", 8 =>"Agosto", 9 => "Setembro", 10 =>"Outubro", 11 => "Novembro", 12 => "Dezembro"); 

          $sql = "select
                      f.financeId,
                      f.value as value,
                      f.categoryId as categoryId,
                      c.name as category,
                      f.monthId as monthId,
                      s.name as subcategoryName
                  from 
                      finances f
                  inner join
                      categories c on c.categoryId = f.categoryId
                  left join
                      sub_categories s on s.sub_categoryId = f.sub_categoryId
                  order by
                      c.name, s.name";
                          
          $result = $conn->query($sql);

          $months = [];
          $categories = [];

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $months[$row['monthId']] = true;
                  $category_subcategory = $row['category'] . ' / ' . $row['subcategoryName'];
                  $categories[$category_subcategory]['financeId'] = $row['financeId'];
                  $categories[$category_subcategory]['category'] = $row['category'];
                  $categories[$category_subcategory]['subcategoryName'] = $row['subcategoryName'];
                  $categories[$category_subcategory]['values'][$row['monthId']]['financeId'][$row['financeId']] = $row['value'];
              }
          }

          echo "<table id='table' class='mx-auto justify-content-center table table-bordered'>";
          echo "<thead>";
          echo "<tr class='table-dark'>";
          echo "<th>Categorias / Subcategorias</th>";
          ksort($months);
          foreach ($months as $monthId => $month) {
              echo "<th>".$monthNames[$monthId] . "</th>";
          }
          echo "<th>Total Anual</th>";
          echo "</tr>";
          echo "</thead>";

          echo "<tbody>";
          foreach ($categories as $category_subcategory => $category) {
            //var_dump($category);
            //die;
              echo "<tr id='row{$category['financeId']}' data-id='{$category['financeId']}' class='table-row'>";
              //echo "<tr id = 'row'{$category['financeId']}>";
              echo "<td>" . $category_subcategory . "</td>";
              $totalCategory = 0;
              foreach ($months as $monthId => $month) {
                //var_dump(array_keys($category['values']));
                if (isset($category['values'][$monthId])) {
                  $financeId = array_keys($category['values'][$monthId]['financeId'])[0];
                  $value = array_values($category['values'][$monthId]['financeId'])[0];
                  echo "<td class='context-menu-one' data-financeId='{$financeId}'> R$ " . number_format($value, 2, ",", ".") . "</td>";
                      $totalCategory += $value;
                  } else {
                      echo "<td></td>";
                  }
              }
              echo "<td class='text-end'> R$ " . number_format($totalCategory, 2, ",", ".") . "</td>";
              echo "</tr>";
        

          }

          // Adicionar linha de Total com a soma de cada mês
          echo "<tr>";
          echo "<td class = 'table-secondary'>Total Mensal:</td>";
          $totalMonths = count($months);
          for ($i = 1; $i <= $totalMonths; $i++) {
              $totalMonth = 0;
              foreach ($categories as $category_subcategory => $category) {
                  if (isset($category['values'][$i])) {
                      //var_dump($category['values'][$i]['financeId']);
                      $totalMonth += array_values($category['values'][$i]['financeId'])[0];
                  }
              }
              echo "<td class='text-end'> R$ " . number_format($totalMonth, 2, ",", ".") . "</td>";
          }
          echo "<td></td>";
          echo "<td></td>";
          echo "</tr>";
          echo "</tbody>";
          echo "</table>";

                
      ?>
        
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="">Editar Registro</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                      </div>
                      <form method="post" action="">
                          <div class="modal-body">
                              <div class="mb-3">
                                  <label for="categorySelect" class="form-label">Categoria:</label>
                                  <select class="form-select" id="categorySelect" name="category" disabled>
                                      <option value="">Selecione...</option>
                                      <?php
                                          $sql = "SELECT * FROM categories";
                                          $result = $conn->query($sql);

                                          if ($result->num_rows > 0) {
                                              while ($row = $result->fetch_assoc()) {
                                                  $categoryId = $row["categoryId"];
                                                  $categoryName = $row["name"];

                                                  echo "<option value='" . $categoryId . "'>" . $categoryName . "</option>";
                                              }
                                          }
                                      ?>
                                  </select>
                              </div>
                              <div class="mb-3">
                                  <label for="subCategorySelect" class="form-label">Subcategoria:</label>
                                  <select class="form-select" id="subCategorySelect" name="subCategory" disabled>
                                      <option value="">Selecione...</option>
                                      <?php
                                          $sql = "SELECT * FROM sub_categories";
                                          $result = $conn->query($sql);

                                          if ($result->num_rows > 0) {
                                              while ($row = $result->fetch_assoc()) {
                                                  $subCategoryId = $row["sub_categoryId"];
                                                  $subCategoryName = $row["name"];

                                                  echo "<option value='" . $subCategoryId . "'>" . $subCategoryName . "</option>";
                                              }
                                          }
                                      ?>
                                  </select>
                              </div>
                              <div class="mb-3">
                                  <label for="monthSelect" class="form-label">Mês:</label>
                                  <select class="form-select" id="monthSelect" name="month" required>
                                      <option value="">Selecione...</option>
                                      <?php
                                      $months= array(1 => 'Janeiro',2 => 'Fevereiro',3 => 'Março',4 => 'Abril',5 => 'Maio',6 => 'Junho',7 => 'Julho',8 => 'Agosto',9 => 'Setembro',10 => 'Outubro',11 => 'Novembro',12 => 'Dezembro');
                                    $monthNamesPt = array(1 => 'Janeiro',2 => 'Fevereiro',3 => 'Março',4 => 'Abril',5 => 'Maio',6 => 'Junho',7 => 'Julho',8 => 'Agosto',9 => 'Setembro',10 => 'Outubro',11 => 'Novembro',12 => 'Dezembro');
                                    foreach ($months as $monthId => $month) {
                                      echo "<option value='" . $monthId . "'>" . $monthNamesPt[$monthId] . "</option>";
                                  }
                              ?>
                                  </select>
                              </div>
                              <div class="mb-3">
                                  <label for="valueInput" class="form-label">Valor:</label>
                                  <input type="text" class="form-control" id="valueInput" name="value" required>
                                  <input type="hidden" name="financeId" id="financeId" value="">

                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              <button type="submit" id="updateFinanceButton" class="btn btn-primary saveBtn" name="saveBtn">Salvar mudanças</button>
                          </div>
                      </form>
                  </div>
                </div>
            </div>
            
            
        <script src = "js/popular_select.js"></script>

     

<script>
    $(document).ready(function() {
      
      $('#formularioDeCadastro').submit( function(event) {
        event.preventDefault();
        const formData = $(this).serialize();
        console.log("teste");
        $.ajax({
            type: 'POST',
            url: 'post_form.php',
            data: formData,
            dataType: 'json',
            success: function(data) {
              console.log("teste");
                setInterval('location.reload()', 100);
            },
            error: function(error) {

                console.log('teste2');
            }
        });
      });

       


        // seleciona o botão de "Salvar" do modal
        //const saveBtn = document.querySelector('button[name="saveBtn"]');

  
        // adiciona um evento de clique ao botão "Salvar"
        $('#updateFinanceButton').on("click", function(event) {
            event.preventDefault();
            console.log('teste');
            //var valorModal = document.getElementById("valueInput").value;
            // obtém os valores do formulário do modal
            let month = $("#monthSelect").val();
            let category = $("#categorySelect").val();
            let subcategory = $("#subCategorySelect").val();
            let value = $("#valueInput").val();
            let financeId = $("#financeId").val();

            $.ajax({
                type: 'POST',
                url: 'updateRecord.php',
                data: {
                //'action' : 'update',
                'financeId' : financeId,
                'categoryId': category,
                'subCategoryId': subcategory,
                'monthId': month,
                'value': value
                
                },
                dataType: 'json',
                success: function(data) {
                  console.log('teste2');
                  //$('#editModal').reset();
                    // Atualiza o valor na tabela
                    
                    // Fecha o modal e limpa os campos
                    $('#editModal').modal('hide');
                    $('#editModal').find('form')[0].reset();  
                    location.reload();
                },
                error: function(error) {
                    console.log('teste3');
                    //alert('Erro ao atualizar registro financeiro');
                }
                
            });
        });
          $.contextMenu({
              selector: '.context-menu-one', 
              callback: function(key, options) {
/*                   var m = "clicked: " + key;
                  window.console && console.log(m) || alert(m);  */

                  if(key == "edit"){
                    //e.preventDefault();
                      const id = $(this).data('financeid');
                      $('#financeId').val(id);
                      $.ajax({
                          type: 'POST',
                          url: 'getRecord.php',
                          data: {
                              'id': id
                          },
                          dataType: 'json',
                          success: function(data) {
                              $('#editModalLabel').html('Editar Registro Financeiro #' + data.id);
                              $('#editModal').find('[name="category"]').val(data.categoryId);
                              $('#editModal').find('[name="subCategory"]').val(data.sub_categoryId);
                              $('#editModal').find('[name="month"]').val(data.monthId);
                              $('#editModal').find('[name="value"]').val(data.value);
                              $('#editModal').modal('show');
                              

                          },
                          error: function() {
                              alert('Erro ao obter dados do registro financeiro.');
                          }
                      });
                } 
                if(key == "delete"){
                  const id = $(this).data('financeid');
                      $('#financeId').val(id);
                      $.ajax({
                          type: 'POST',
                          url: 'deleteRecord.php',
                          data: {
                              'financeId': id
                          },
                          dataType: 'json',
                          success: function(data) {
                              location.reload();
                          },
                          error: function() {
                              alert('Erro ao obter dados do registro financeiro.');
                          }
                      });
                }
              },


              items: {
                  "edit": {name: "Edit", icon: "edit"},
                  "delete": {name: "Delete", icon: "delete"},
                  "quit": {name: "Quit", icon: function(){
                      return 'context-menu-icon context-menu-icon-quit';
                  }}
              }
          });

          $('.context-menu-one').on('click', function(e){
              console.log('teste');
              
              })    
          });


</script>
      



</body>
</html>
