<?php
    include_once "configs/config.php";
?>

<script src="global/js/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js"></script>
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo Config::$APP_FAVICON;?>">
<script>
    $(window).on('load', function() {
        if (Cookies.get('dark') == "true"){
            $("body").addClass("dark-theme").stop(true, true);
        }
    });
</script>