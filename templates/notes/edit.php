<section class="card form-card">
    <span class="eyebrow">Изменение записи</span>
    <h1>Редактировать заметку</h1>
    <p class="muted">Измените данные и сохраните результат.</p>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= e($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/notes/edit">
        <input type="hidden" name="id" value="<?= e((string)$note['id']) ?>">

        <label>Название</label>
        <input type="text" name="title" value="<?= e($note['title']) ?>">

        <label>Категория</label>
        <input type="text" name="category" value="<?= e($note['category']) ?>">

        <label>Текст заметки</label>
        <textarea name="text" rows="9"><?= e($note['text']) ?></textarea>

        <div class="form-actions">
            <button type="submit">Сохранить изменения</button>
            <a class="button secondary" href="/notes">Отмена</a>
        </div>
    </form>
</section>
