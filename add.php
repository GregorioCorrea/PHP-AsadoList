<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\EdmType;

ini_set('display_errors', 1);
error_reporting(E_ALL);

$accountName = getenv('ACCOUNT_NAME');
$accountKey = getenv('ACCOUNT_KEY');
$tableName = "ShoppingList";

$item = $_POST['item'] ?? '';
$quantity = $_POST['quantity'] ?? '';

if ($item === '' || !is_numeric($quantity)) {
    echo "Error: Falta nombre del ítem o cantidad inválida.";
    exit;
}

$connectionString = "DefaultEndpointsProtocol=https;AccountName=$accountName;AccountKey=$accountKey;TableEndpoint=https://$accountName.table.core.windows.net/";
$tableClient = TableRestProxy::createTableService($connectionString);

$entity = new Entity();
$entity->setPartitionKey("shopping");
$entity->setRowKey(preg_replace('/[\/\\\\#\?\[\]]/', '_', $item));

// ✅ Forma correcta y soportada en tu versión
$entity->addProperty("quantity", EdmType::INT32, (int)$quantity);

try {
    $tableClient->insertOrMergeEntity($tableName, $entity);
    header("Location: /");
    exit;
} catch (Exception $e) {
    echo "<h3>Error al insertar entidad</h3>";
    echo "<pre>" . $e . "</pre>";
}
