<section class="card form-card">
    <span class="eyebrow">Удаление</span>
    <h1>Удалить заметку</h1>
    <p class="muted">Подтвердите удаление выбранной заметки.</p>

    <article class="note-card compact">
        <h2><?= e($note['title']) ?></h2>
        <p class="badge"><?= e($note['category']) ?></p>
        <p class="note-text"><?= nl2br(e($note['text'])) ?></p>
    </article>

    <form method="post" action="/notes/delete">
        <input type="hidden" name="id" value="<?= e((string)$note['id']) ?>">

        <div class="form-actions">
            <button class="danger-button" type="submit">Удалить</button>
            <a class="button secondary" href="/notes">Отмена</a>
        </div>
    </form>
</section>
