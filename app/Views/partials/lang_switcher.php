<form method="POST" action="/set_lang.php" class="d-inline-block ms-2">
    <select name="lang" class="form-select form-select-sm d-inline-block" style="width:auto;" onchange="this.form.submit()">
        <option value="en" <?= ($_SESSION['lang'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
        <option value="ar" <?= ($_SESSION['lang'] ?? '') === 'ar' ? 'selected' : '' ?>>العربية</option>
    </select>
</form>
