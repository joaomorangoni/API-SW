<?php
header("Content-Type: application/json");

$dataFile = 'data.json';
$data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

function saveData($dataFile, $data) {
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
}

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$body = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if ($id !== null) {
            foreach ($data as $contato) {
                if ($contato['id'] == $id) {
                    echo json_encode($contato);
                    exit;
                }
            }
            http_response_code(404);
            echo json_encode(["erro" => "Contato não encontrado"]);
        } else {
            echo json_encode($data);
        }
        break;

    case 'POST':
        if (!$body || !isset($body['nome']) || !isset($body['email']) || !isset($body['numero'])) {
            http_response_code(400);
            echo json_encode(["erro" => "Dados inválidos"]);
            exit;
        }

        if (empty($data)) {
            $novoId = 1;
        } else {
            $ids = array_column($data, 'id');
            $novoId = max($ids) + 1;
        }

        $novoContato = [
            "id" => $novoId,
            "nome" => $body['nome'],
            "email" => $body['email'],
            "numero" => $body['numero']
        ];
        $data[] = $novoContato;
        saveData($dataFile, $data);
        echo json_encode($novoContato);
        break;

    case 'PUT':
        if ($id === null || !$body) {
            http_response_code(400);
            echo json_encode(["erro" => "ID e dados obrigatórios"]);
            exit;
        }

        $atualizado = false;
        foreach ($data as &$contato) {
            if ($contato['id'] == $id) {
                $contato['nome'] = $body['nome'] ?? $contato['nome'];
                $contato['email'] = $body['email'] ?? $contato['email'];
                $contato['numero'] = $body['numero'] ?? $contato['numero'];
                $atualizado = true;
                break;
            }
        }

        if ($atualizado) {
            saveData($dataFile, $data);
            echo json_encode(["mensagem" => "Contato atualizado com sucesso"]);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Contato não encontrado"]);
        }
        break;

    case 'DELETE':
        if ($id === null) {
            http_response_code(400);
            echo json_encode(["erro" => "ID obrigatório"]);
            exit;
        }

        $novoArray = array_filter($data, fn($c) => $c['id'] != $id);

        if (count($novoArray) !== count($data)) {
            saveData($dataFile, array_values($novoArray));
            echo json_encode(["mensagem" => "Contato deletado com sucesso"]);
        } else {
            http_response_code(404);
            echo json_encode(["erro" => "Contato não encontrado"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido"]);
}
