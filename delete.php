<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Table\Models\Entity;

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración
$accountName = getenv('ACCOUNT_NAME');
$accountKey = getenv('ACCOUNT_KEY');
$tableName = "ShoppingList";

// Validación de datos
$rowKey = $_POST['rowkey'] ?? '';
$partitionKey = "shopping";

if ($rowKey === '') {
    echo "Error: No se especificó el item a eliminar.";
    exit;
}

// Crear conexión
$connectionString = "DefaultEndpointsProtocol=https;AccountName=$accountName;AccountKey=$accountKey;TableEndpoint=https://$accountName.table.core.windows.net/";
$tableClient = TableRestProxy::createTableService($connectionString);

try {
    $tableClient->deleteEntity($tableName, $partitionKey, $rowKey);
    header("Location: /");
    exit;
} catch (Exception $e) {
    echo "<h3>Error al eliminar entidad</h3>";
    echo "<pre>" . $e . "</pre>";
}
