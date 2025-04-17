<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración
$accountName = getenv('ACCOUNT_NAME');
$sasToken = getenv('ACCOUNT_SAS'); // ⚠️ NUEVA VARIABLE DE ENTORNO
$tableName = "ShoppingList";
$item = $_POST['item'] ?? '';
$quantity = $_POST['quantity'] ?? '';

if ($item === '' || !is_numeric($quantity)) {
    echo "Faltan datos válidos.";
    exit;
}

$entity = [
    'PartitionKey' => 'shopping',
    'RowKey' => preg_replace('/[\/\\\\#\?\[\]]/', '_', $item),
    'quantity' => (int)$quantity
];

$body = json_encode($entity);
$date = gmdate('D, d M Y H:i:s T');

// Construir URL con token SAS
$url = "https://$accountName.table.core.windows.net/$tableName?$sasToken";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Content-Type: application/json",
    "x-ms-date: $date",
    "x-ms-version: 2019-02-02",
    "DataServiceVersion: 3.0;NetFx",
    "MaxDataServiceVersion: 3.0;NetFx"
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode >= 200 && $httpCode < 300) {
    header("Location: /");
    exit;
} else {
    echo "<h3>Error al insertar entidad</h3>";
    echo "<pre>HTTP $httpCode\n$response</pre>";
}
