<?php

declare(strict_types=1);

?>
<header class="p-sm-3 pt-3 mb-2 border-bottom">
    <div class="container">
        <div class="row d-flex justify-content-center justify-content-sm-start">
            <a href="/" class="col-auto d-flex">
                <img src="/images/logo_white.svg" alt="Логотип белый" class="logo logo-white">
                <img src="/images/logo_black.svg" alt="Логотип белый" class="logo logo-black">
            </a>

            <form class="col-12 col-sm-auto my-3 my-sm-0 d-flex align-items-center" action="/" method="GET">
                <input type="search"
                       class="form-control form-control-dark"
                       placeholder="Поиск..."
                       aria-label="Search"
                       name="s"
                       value="<?php echo app()->search_string ?>">
            </form>
        </div>
    </div>
</header>
