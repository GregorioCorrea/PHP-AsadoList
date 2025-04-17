<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Table\TableRestProxy;

$accountName = getenv('ACCOUNT_NAME');
$accountKey = getenv('ACCOUNT_KEY');

$connectionString = "DefaultEndpointsProtocol=https;AccountName=$accountName;AccountKey=$accountKey;TableEndpoint=https://$accountName.table.core.windows.net/";
$tableClient = TableRestProxy::createTableService($connectionString);

$tableName = "ShoppingList";

// Obtener entidades
$filter = "PartitionKey eq 'shopping'";
$result = $tableClient->queryEntities($tableName, $filter);
$entities = $result->getEntities();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Compras</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Lista de Compras - PHP + Azure Table Storage</h1>

    <form action="add.php" method="POST" class="form-inline my-4">
        <input type="text" name="item" class="form-control mr-2" placeholder="Item" required>
        <input type="number" name="quantity" class="form-control mr-2" placeholder="Cantidad" required>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>

    <ul class="list-group">
    <?php foreach ($entities as $entity): ?>
        <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <form action="delete.php" method="POST" class="m-0">
                        <input type="hidden" name="item" value="<?= htmlspecialchars($entity->getRowKey()) ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm mr-2" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <span><?= htmlspecialchars($entity->getRowKey()) ?></span>
                    <span class="badge badge-primary badge-pill ml-2">
                        <?= $entity->getProperty("quantity")->getValue() ?>
                    </span>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

</div>
</body>
</html>
