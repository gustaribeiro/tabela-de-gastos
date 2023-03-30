<?php
//error_reporting(0);

include_once ("connection.php");



if(isset($_POST['form_submitted']) && $_POST['form_submitted'] == "true"){
  $nome = $_POST['first_name'];
  $email = $_POST['email'];
  $senha = $_POST['password'];
  $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

  $sql= "SELECT* FROM users WHERE email='$email'";
  $result=$conn->query($sql);
  //se retornar do banco número de linhas maior que 0, if result
  if($result->num_rows > 0){
    echo"email já cadastrado";
    die();
  }
  
  $sql="INSERT INTO users(first_name,email,password) VALUES('$nome','$email','$senhaCriptografada')";
  if($conn->query($sql)===true){
    echo"ok";
  }else{
    echo"error {$sql}";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Cadastro</title>
    <style>
   
      input{
        max-width: 500px;
      }
      .container{
        margin-top: 50px;
      }
      h1{
        text-align: center;
      }
      .enviar{
        display: flex;
        justify-content: center;
        align-items: center;
        
      }
      #linkLoginPage{
        text-decoration: none;
        outline: none;
        color: black;
        margin-top: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
      }


      
    </style>
</head>
<body>
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
            <a class="nav-link" href="#">Login</a>
            <a class="nav-link" href="#">Cadastro</a>
            <a class="nav-link active" aria-current="page" href="#">Tabela</a>
          </div>
        </div>
      </div>
    </nav>


    <div class="container col-sm-4 red">
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
          <h1>Cadastro</h1>
          <br>
          <div class="form-group">
              <label for="exampleInputEmail1">Nome:</label>
              <input type="text" name="first_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Seu Nome" required>
          </div>
            <br>
          <div class="form-group">
              <label for="exampleInputEmail1">Endereço de email:</label>
              <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Seu email" required>
          </div>
          <br>
          <div class="form-group">
              <label for="exampleInputPassword1">Senha:</label>
              <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Senha" required>
          </div>
            <br>
          <input type="submit" class="btn btn-outline-dark enviar mx-auto d-flex justify-content-center" value="Enviar">
            <br>
            <!--verificação adicional para garantir que o form esteja sendo enviado antes de inserir as informações no banco-->
          <input type="hidden" name="form_submitted" value="true">


      </form>

          <div id="linkLoginPage">
            <a class="link-dark" href="login.php">Já tenho uma conta</a>
          </div>

    </div>


    
</body>
</html>



