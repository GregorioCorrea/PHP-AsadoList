<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Table\TableRestProxy;

$accountName = getenv('ACCOUNT_NAME');
$accountKey = getenv('ACCOUNT_KEY');

$connectionString = "DefaultEndpointsProtocol=https;AccountName=$accountName;AccountKey=$accountKey;TableEndpoint=https://$accountName.table.core.windows.net/";
$tableClient = TableRestProxy::createTableService($connectionString);

$tableName = "ShoppingList";

$item = $_POST['item'] ?? null;

if (!$item) {
    echo "Error: No se especificó el ítem a eliminar.";
    exit;
}

try {
    $tableClient->deleteEntity($tableName, 'shopping', $item);
} catch (Exception $e) {
    echo "Error al eliminar el ítem: " . $e->getMessage();
    exit;
}

header("Location: /");
exit;
