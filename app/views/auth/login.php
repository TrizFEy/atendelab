<form method="post" action="<?= $baseUrl ?>?controller=auth&action=entrar">
    <div class="mb-3">
        <label for="email" class="form-label">
            E-mail
        </label>
        <input type="email" class="form-control" id="email" name="email" required autofocus>
    </div>
    <div class="mb-4">
        <label for="senha" class="form-label">
            Senha
        </label>
        <input type="password" class="form-control" id="senha" name="senha" required>
    </div>
    <button class="btn btn-success w-100" type="submit">
        Entrar
    </button>
</form>
