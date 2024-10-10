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
            <form class="row g-3">
                <div class="col-12">
                    <h3 class="m-1">Ingreso al sistema</h4>
                </div>
                <div class="col-12">
                    <label class="form-label" for="usr">Correo electrónico</label>
                    <input id="usr" type="text" class="form-control me-5" placeholder="Correo electrónico" required="">
                </div>
                <div class="col-12">
                    <label class="form-label" for="psw">Contraseña</label>
                    <input id="psw" type="password" class="form-control me-5" placeholder="Contraseña" required="">
                </div>
                <div class="col-12">
                    <div class="g-recaptcha" id="grecaptcha" data-sitekey="6LeEZRYnAAAAAGtKUoYCq_uXp0ZTlTlIkk2XIp2i"><div style="width: 304px; height: 78px;"><div><iframe title="reCAPTCHA" width="304" height="78" role="presentation" name="a-ahnzpvm82hzm" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox allow-storage-access-by-user-activation" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LeEZRYnAAAAAGtKUoYCq_uXp0ZTlTlIkk2XIp2i&amp;co=aHR0cHM6Ly9jb25jaWxpYWNpb24uY2VudHJvbGFib3JhbC5nb2IubXg6NDQz&amp;hl=es-419&amp;v=EGbODne6buzpTnWrrBprcfAY&amp;size=normal&amp;cb=3qz2qiaidzt7"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe style="display: none;"></iframe></div>
                </div>
                <div class="col-12">
                    <button id="LogIn" type="button" class="form-control btn btn-red">Ingresar</button>
                </div>
                <div class="col-12">
                    <p>¿No está registrado?</p>
                    <a class="form-control btn btn-color rounded submit px-3" href="nuevousuario" >Registrarse</a>
                </div>
                <div class="col-12">
                    <a href="recupera">Recuperar Contraseña</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include_once dirname(__FILE__) . "/Footer.php"; ?>
