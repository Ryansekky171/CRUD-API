<?php
require_once('con.php');

class Crud {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Endpoint GET: Listar Todas as Viagens
    public function listViagens() {
        $result = $this->conn->query("SELECT * FROM viagens");
        $viagens = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($viagens);
    }

    // Endpoint GET: Detalhes de Uma Viagem Específica
    public function getViagem($id) {
        $result = $this->conn->query("SELECT * FROM viagens WHERE id = $id");
        $viagem = $result->fetch_assoc();
        echo json_encode($viagem);
    }

    // Endpoint POST: Criar Nova Viagem
    public function createViagem($destino, $descricao, $data_partida, $data_retorno) {
        $stmt = $this->conn->prepare("INSERT INTO viagens (destino, descricao, data_partida, data_retorno) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $destino, $descricao, $data_partida, $data_retorno);
    
        if ($stmt->execute()) {
            echo json_encode(array("message" => "Viagem criada com sucesso!"));
        } else {
            echo json_encode(array("message" => "Erro ao criar viagem.", "error" => $stmt->error));
        }
    
        $stmt->close();
    }

    // Endpoint POST: Atualizar Viagem Existente
    public function updateViagem($id, $destino, $descricao, $data_partida, $data_retorno) {
        $this->conn->query("UPDATE viagens SET destino='$destino', descricao='$descricao', data_partida='$data_partida', data_retorno='$data_retorno' WHERE id=$id");
        echo "Viagem atualizada com sucesso!";
    }

    // Endpoint POST: Remover Viagem
    public function deleteViagem($id) {
        $this->conn->query("DELETE FROM viagens WHERE id=$id");
        echo "Viagem removida com sucesso!";
    }
}

// Tratamento de Requisição
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $crud = new Crud($conn);

    // Verifica se a chave 'action' está definida
    if (isset($_GET['action'])) {
        // Verifica o valor da chave 'action' para determinar a operação
        if ($_GET['action'] === 'list') {
            $crud->listViagens();
        } elseif ($_GET['action'] === 'one' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $crud->getViagem($id);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $crud = new Crud($conn);

    // Verifica o valor da chave 'action' para determinar a operação
    if ($_GET['action'] === 'new' && isset($_POST['destino'], $_POST['descricao'], $_POST['data_partida'], $_POST['data_retorno'])) {
        $destino = $_POST['destino'];
        $descricao = $_POST['descricao'];
        $data_partida = $_POST['data_partida'];
        $data_retorno = $_POST['data_retorno'];
        $crud->createViagem($destino, $descricao, $data_partida, $data_retorno);
    } elseif ($_GET['action'] === 'update' && isset($_POST['id'], $_POST['destino'], $_POST['descricao'], $_POST['data_partida'], $_POST['data_retorno'])) {
        $id = $_POST['id'];
        $destino = $_POST['destino'];
        $descricao = $_POST['descricao'];
        $data_partida = $_POST['data_partida'];
        $data_retorno = $_POST['data_retorno'];
        $crud->updateViagem($id, $destino, $descricao, $data_partida, $data_retorno);
    } elseif ($_GET['action'] === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $crud->deleteViagem($id);
    }
}
?>
