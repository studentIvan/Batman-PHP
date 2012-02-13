<?php
require_once '../../autoload.php';
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\Yui;

$js = new AssetCollection(array(
    new FileAsset('app/root/styles/twitter/bootstrap.min.css'),
    new FileAsset('app/root/styles/admin/admin.css'),
));

// the code is merged when the asset is dumped
echo htmlspecialchars($js->dump());