<section class="card big-card">
    <div class="page-heading">
        <div>
            <h1>Каталог заметок</h1>
            <p class="muted">Выберите категорию, чтобы показать нужные записи.</p>
        </div>
        <a class="button" href="/notes/add">+ Добавить</a>
    </div>

    <?php if ($message !== ''): ?>
        <p class="success"><?= e($message) ?></p>
    <?php endif; ?>

    <form class="filter-form" method="get" action="/notes">
        <div class="filter-field">
            <label for="category">Категория</label>
            <select id="category" name="category">
                <option value="">Все категории</option>
                <?php foreach ($categories as $item): ?>
                    <option value="<?= e($item) ?>" <?= $category === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="filter-button" type="submit">Применить</button>
        <a class="button secondary small reset-button" href="/notes">Сбросить</a>
    </form>

    <?php if (empty($notes)): ?>
        <p class="empty">Заметок пока нет.</p>
    <?php else: ?>
        <div class="notes-list">
            <?php foreach ($notes as $note): ?>
                <article class="note-card">
                    <div class="note-header">
                        <div>
                            <h2><?= e($note['title']) ?></h2>
                            <p class="badge"><?= e($note['category']) ?></p>
                        </div>
                        <div class="note-actions">
                            <a href="/notes/edit?id=<?= e((string)$note['id']) ?>">Редактировать</a>
                            <a class="danger-link" href="/notes/delete?id=<?= e((string)$note['id']) ?>">Удалить</a>
                        </div>
                    </div>

                    <p class="note-text"><?= nl2br(e($note['text'])) ?></p>

                    <div class="note-meta">
                        <span>Символов: <?= e((string)textLength($note['text'])) ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
