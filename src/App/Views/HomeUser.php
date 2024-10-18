<?php
namespace Sk\App\Views;

include_once dirname(__FILE__) . "/HeaderSess.php";
?>
<div class="container p-3">
    <div class="row mb-2">
        <div class="col-md-6 col-sm-12">
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="col-auto d-none d-lg-block p-2">
                    <img src="data:image/png;base64,<?=$data['userImage']?>" width="150" class="user-image rounded-circle shadow" alt="">
                </div>
                <div class="col p-2 d-flex flex-column ">
                    <strong class="d-inline-block mb-2 text-primary-emphasis">Bienvenido</strong>
                    <?php foreach($data['userData'] as $k => $v): ?>
                        <span><?=$k?>: <?=is_array($v) ? '<pre>'. str_replace(',',"\n",json_encode($v)).'</pre>' : $v?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once dirname(__FILE__) . "/Footer.php"; ?>
