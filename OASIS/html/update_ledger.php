<?php
header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "password";
$database = "oasis";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$account = $_POST['account'];
$changes = json_decode($_POST['changes'], true);

// Group changes by table and id
$groupedChanges = [];
foreach ($changes as $change) {
    $table = $change['table'];
    $id = $change['id'];
    if (!isset($groupedChanges[$table])) {
        $groupedChanges[$table] = [];
    }
    if (!isset($groupedChanges[$table][$id])) {
        $groupedChanges[$table][$id] = [];
    }
    $groupedChanges[$table][$id][$change['field']] = $conn->real_escape_string($change['value']);
}

// Process each group of changes
foreach ($groupedChanges as $table => $ids) {
    $tableName = $account . '_' . $table;
    foreach ($ids as $id => $fields) {
        if ($id === 'new') {
            // Insert new row with only fee, duedate, and amount
            if (!isset($fields['fee']) || !isset($fields['duedate']) || !isset($fields['amount'])) {
                echo json_encode(['success' => false, 'error' => 'Missing required fields for new row']);
                $conn->close();
                exit;
            }
            $stmt = $conn->prepare("INSERT INTO `$tableName` (fee, duedate, amount) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $fields['fee'], $fields['duedate'], $fields['amount']);
            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'error' => 'Insert failed: ' . $conn->error]);
                $stmt->close();
                $conn->close();
                exit;
            }
            $stmt->close();
        } else {
            // Update existing row with specified fields only
            $updates = [];
            $params = [];
            foreach ($fields as $field => $value) {
                $updates[] = "$field = ?";
                $params[] = $value;
            }
            if (!empty($updates)) {
                $stmt = $conn->prepare("UPDATE `$tableName` SET " . implode(', ', $updates) . " WHERE id = ?");
                $params[] = $id;
                $types = str_repeat('s', count($params) - 1) . 'i'; // String for values, Integer for id
                $stmt->bind_param($types, ...$params);
                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'error' => 'Update failed: ' . $conn->error]);
                    $stmt->close();
                    $conn->close();
                    exit;
                }
                $stmt->close();
            }
        }
    }
}

echo json_encode(['success' => true]);
$conn->close();
?>