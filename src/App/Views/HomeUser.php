<?php
namespace Sk\App\Views;

include_once dirname(__FILE__) . "/Header.php";
?>
<div class="container p-3">
    <div class="row">
        <div class="col-md-3 col-sm-12 order-md-1 order-sm-2 order-2">
            <img src="data:image/jpeg;base64,<?=$data['userImage']?>" class="img-fluid" alt="">
        </div>
        <div class="col-md-3 col-sm-12 order-md-2 order-sm-1 order-1">
            <ul>
            <?php foreach($data['userData'] as $k => $v): ?>
                <li><b><?=$k?>: </b> <?=$v?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php include_once dirname(__FILE__) . "/Footer.php"; ?>
