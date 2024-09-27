<?php
namespace Sk\App\Views;

include_once dirname(__FILE__) . "/Header.php";
?>
<div class="container p-3">
    <div class="row">
        <div class="col-md-6 col-sm-12 order-md-1 order-sm-2 order-2">
            <img src="<?=$pathAssets?>/img/logoGob.svg" class="img-fluid" alt="">
        </div>
        <div class="col-md-6 col-sm-12 order-md-2 order-sm-1 order-1">
            <div class="row g-3">
                <div class="col-12 text-center">
                    <h4 class="mb-3">Bienvenido</h4>
                    <h4 class="mb-3"><?=$data["sistema"]?></h4>
                    <a href="login" class="btn btn-red">Log In</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once dirname(__FILE__) . "/Footer.php"; ?>
