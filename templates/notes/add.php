<section class="card form-card">
    <span class="eyebrow">Новая запись</span>
    <h1>Добавить заметку</h1>
    <p class="muted">Заполните поля и сохраните заметку.</p>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/notes/add">
        <label>Название</label>
        <input type="text" name="title" value="<?= e($old['title']) ?>" placeholder="Например: Подготовка к защите">

        <label>Категория</label>
        <input type="text" name="category" value="<?= e($old['category']) ?>" placeholder="Например: Учёба">

        <label>Текст заметки</label>
        <textarea name="text" rows="9" placeholder="Введите текст заметки..."><?= e($old['text']) ?></textarea>

        <div class="form-actions">
            <button type="submit">Сохранить</button>
            <a class="button secondary" href="/notes">Отмена</a>
        </div>
    </form>
</section>
