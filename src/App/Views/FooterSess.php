</main>

    <link href="<?=$pathAssets?>/css/footer.css" rel="stylesheet" />
    <footer class="app-footer footer"> <!--begin::To the end-->
        <div class="footer-encabezado"></div>
            <div class="footer-contenedor">
                <div class="footer-logo">

                    <img src="logo.svg" alt="">
                </div>
                <div class="footer-opciones">
                    <a class ="col" href="https://www.gob.mx/avisos-de-privacidad" target="_blank">
                        Aviso de Privacidad
                    </a>
                    <a class ="col" href="https://www.gob.mx/" target="_blank">
                        Site brand footer <br>
                        large text
                    </a>
                    <a class ="col" href="https://www.gob.mx/" target="_blank">
                        Site brand footer
                    </a>
                    <a class ="col" href="https://www.gob.mx/" target="_blank">
                        Site brand footer
                    </a>
                </div>
            </div>
            <div class="footer-decoracion">
                <img src="https://repositorio.centrolaboral.gob.mx/images/icons/pattern.svg" alt="">
                <img src="<?=$pathAssets?>/img/pattern.svg" alt="">
            </div>
        </div>
    </footer> <!--end::Footer-->
</div> <!--end::App Wrapper-->

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script>
    <!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body><!--end::Body-->
</html>
