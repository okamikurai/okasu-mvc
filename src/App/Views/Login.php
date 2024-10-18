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
                    <div id="msg" class="alert alert-danger d-flex align-items-center invisible" role="alert" d-></div>
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
                    <div class="g-recaptcha" id="grecaptcha" data-sitekey="6LeEZRYnAAAAAGtKUoYCq_uXp0ZTlTlIkk2XIp2i"></div>
                </div>
                <div class="col-12">
                    <button id="btnLogIn" type="button" class="form-control btn btn-red">Ingresar</button>
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

<script>
    document.addEventListener("DOMContentLoaded", function(e) {
        const btnLogIn = document.querySelector("#btnLogIn")
        const msg = document.querySelector("#msg")

        btnLogIn.addEventListener("click", async function(e){
            e.preventDefault()
            if (document.querySelector("#usr").value == "" || document.querySelector("#psw").value == "") {
                msg.innerHTML = "Los datos no pueden ir vacíos";
                msg.classList.remove("invisible")
                setTimeout(function() {
                    msg.classList.add("invisible")
                }, 2000)
                return false
            }
            document.getElementById('lockscreen').style.display = 'block'
            const uri = "<?=$data["postUri"]?>"

            const fd = new FormData()
            fd.append("usr", document.querySelector("#usr").value)
            fd.append("psw", document.querySelector("#psw").value)
            const opts = {
                method: "POST",
                body: fd
            }
            await fetch(uri, opts)
                .then(async function(res){
                    document.getElementById('lockscreen').style.display = 'none'
                    const data = await res.json()
                    if (data.error>0) {
                        msg.innerHTML = data.msg;
                        msg.classList.remove("invisible")
                        setTimeout(function() {
                            msg.classList.add("invisible")
                        }, 2000)
                    }else {
                        console.log(data.access)
                        window.location.href = data.access
                    }
                })
                .catch(function (e) {
                    console.log("Hubo un problema con la petición" + e.message)
                    msg.classList.remove("invisible")
                    setTimeout(function() {
                        msg.classList.add("invisible")
                    }, 2000)
                });
            


            

        })
    });
</script>
<?php include_once dirname(__FILE__) . "/Footer.php"; ?>
