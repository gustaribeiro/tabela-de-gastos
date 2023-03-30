<?php
//error_reporting(0);

include_once ("connection.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  
  $email = $_POST['email'];
  $senha = $_POST['password'];

  //verifica se a conexão com o banco foi estabelecida
  if(!$conn){
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
  }
  //verifica o usuário
  $sql= "SELECT * FROM users WHERE email='$email'";
  $result=$conn->query($sql);

  if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    //verifica senha
    if (password_verify($senha, $row['password'])) {
      session_start();
      $_SESSION['userId'] = $row['userId'];
      $_SESSION['first_name'] = $row['first_name'];
      $_SESSION['email'] = $row['email'];

      header('location: tabela.php');
    }else{
      echo"email ou senha errados";
    }
  }else{
    echo"Usuário não encontrado";
  }
}
$conn->close();//fecha conexão com banco de dados 
?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Login</title>
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
      #linkCadastroPage{
        display: flex;
        align-items: center;
        justify-content: center;
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


    <div class="container col-sm-4 red  ">
      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
          <h1>Login</h1>
          <br>
            <div class="form-group">
                <label for="exampleInputEmail1">Endereço de email</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Seu email" required>
            </div>
            <br>
            <div class="form-group">
                <label for="exampleInputPassword1">Senha</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Senha" required>
            </div>
            <br>
            <input type="submit" class="btn btn-outline-dark mx-auto d-flex justify-content-center" value="Enviar">
            <br><br>
            
          </form>
          
          <div id="linkCadastroPage">
            <a class="link-dark" href="cadastro.php">Não tenho uma conta</a>
          </div>

    </div>


    
</body>
</html>