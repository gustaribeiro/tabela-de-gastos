<?php

/* var_dump($_GET['id']);
die; */
// Conexão com o banco de dados
include_once('connection.php');


// Verifica se a conexão ocorreu sem erros
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o parâmetro 'id' foi recebido
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Consulta SQL para buscar as informações do registro
    $sql = "SELECT * FROM finances WHERE financeId = $id";
    $result = $conn->query($sql);

    // Verifica se a consulta retornou algum resultado
    if ($result->num_rows > 0) {
        // Converte o resultado em um array associativo
        $row = $result->fetch_assoc();

        // Retorna as informações do registro no formato JSON
        echo json_encode($row);
    } else {
        // Se o registro não for encontrado, retorna uma mensagem de erro
        echo json_encode(array("error" => "Registro não encontrado"));
    }
} else {
    // Se o parâmetro 'id' não for recebido, retorna uma mensagem de erro
    echo json_encode(array("error" => "Parâmetro 'id' não encontrado"));
}

// Fecha a conexão com o banco de dados
$conn->close();


