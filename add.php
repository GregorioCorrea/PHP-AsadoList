<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\EdmType;
use MicrosoftAzure\Storage\Table\Models\EntityProperty;

$accountName = getenv('ACCOUNT_NAME');
$accountKey = getenv('ACCOUNT_KEY');

$connectionString = "DefaultEndpointsProtocol=https;AccountName=$accountName;AccountKey=$accountKey;TableEndpoint=https://$accountName.table.core.windows.net/";
$tableClient = TableRestProxy::createTableService($connectionString);

$tableName = "ShoppingList";

$item = $_POST['item'] ?? '';
$quantity = $_POST['quantity'] ?? 0;

if ($item && $quantity) {
    $entity = new Entity();
    $entity->setPartitionKey("shopping");
    $entity->setRowKey($item);
    $entity->addProperty("quantity", EntityProperty::createEntityPropertyForInt32((int)$quantity));

    try {
        $tableClient->insertOrMergeEntity($tableName, $entity);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

header("Location: index.php");
exit;
