<main class="form-signin text-center">
    <form method="post" action="<?php echo app()->router->getRoutePath('login') ?>">
        <img class="mb-4" src="/images/logo_white.svg" alt="" width="72" height="57">

        <div class="form-floating">
            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" required>
            <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
            <label for="floatingPassword">Пароль</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Войти</button>
    </form>
</main>