<?php

session_start();

// Configurações do banco de dados padrão do XAMPP
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "trabalho helbert"; 

// Cria conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores do formulário
    $sala = $_POST["sala"];
    $data = $_POST["data"];
    $horario = $_POST["horario"];
    $nome = $_POST["nome"];

    // Verifica se a sala está disponível nessa data e horário
    $sql_disponibilidade = "SELECT * FROM Reservas WHERE id_sala = '$sala' AND data = '$data' AND horario = '$horario'";
    $result = $conn->query($sql_disponibilidade);

    if ($result->num_rows == 0) {
        // Sala está disponível, realiza a reserva
        $sql_reserva = "INSERT INTO Reservas (id_sala, data, horario, nome_requisitante) VALUES ('$sala', '$data', '$horario', '$nome')";

        if ($conn->query($sql_reserva) === TRUE) {
            echo "<p class='text-success'>Reserva realizada com sucesso!</p>";
        } else {
            echo "<p class='text-danger'>Erro ao realizar a reserva: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='text-danger'>Sala já está reservada para esse horário. Por favor, escolha outro horário.</p>";
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alocação de Salas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Alocação de Salas</h1>
        <form method="post">
            <div class="form-group">
                <label for="nome">Nome do Requisitante:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="sala">Sala:</label>
                <select class="form-control" id="sala" name="sala" required>
                    <option value="">Selecione a Sala</option>
                    <?php
                    // Conexão com o banco de dados
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "trabalho helbert"; // Nome do seu banco de dados

                    $conn = new mysqli($servername, $username, $password, $database);

                    if ($conn->connect_error) {
                        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
                    }

                    $sql_salas = "SELECT * FROM Salas";
                    $result_salas = $conn->query($sql_salas);

                    if ($result_salas->num_rows > 0) {
                        while($row = $result_salas->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
                        }
                    } else {
                        echo "<option disabled>Nenhuma sala encontrada</option>";
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" class="form-control" id="data" name="data" required>
            </div>
            <div class="form-group">
                <label for="horario">Horário:</label>
                <select class="form-control" id="horario" name="horario" required>
                    <option value="">Selecione o Horário</option>
                    <?php
                    $horariosDisponiveis = array(
                        "09:00",
                        "10:00",
                        "11:00",
                        "14:00",
                        "15:00",
                        "16:00"
                    );

                    foreach ($horariosDisponiveis as $horario) {
                        echo "<option value='$horario'>$horario</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Reservar</button>
        </form>
    </div>
</body>
</html>
