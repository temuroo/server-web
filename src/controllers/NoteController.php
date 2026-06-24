<?php
// Защита от прямого открытия.
if (!defined('APP_STARTED')) {
    exit('Доступ запрещён.');
}

// Контроллер страниц заметок.
class NoteController
{
    private NoteRepository $repository;

    public function __construct(NoteRepository $repository)
    {
        $this->repository = $repository;
    }

    // Главная.
    public function home(): void
    {
        $this->render('home.php', ['title' => 'Главная']);
    }

    // Список заметок.
    public function index(): void
    {
        $notes = $this->repository->all();
        $category = trim($_GET['category'] ?? '');

        if ($category !== '') {
            $notes = array_filter($notes, function (array $note) use ($category) {
                return textLower($note['category']) === textLower($category);
            });
        }

        $this->render('notes/index.php', [
            'title' => 'Все заметки',
            'notes' => array_values($notes),
            'category' => $category,
            'categories' => $this->getCategories($this->repository->all()),
            'message' => $this->getFlashMessage(),
        ]);
    }

    // Форма добавления.
    public function add(): void
    {
        $this->render('notes/add.php', [
            'title' => 'Добавить заметку',
            'errors' => [],
            'old' => ['title' => '', 'category' => '', 'text' => ''],
        ]);
    }

    // Сохранение заметки.
    public function store(): void
    {
        $data = $this->getFormData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->render('notes/add.php', [
                'title' => 'Добавить заметку',
                'errors' => $errors,
                'old' => $data,
            ]);
            return;
        }

        $this->repository->create($data);
        $this->setFlashMessage('Заметка добавлена.');
        $this->redirect('/notes');
    }

    // Форма редактирования.
    public function edit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $note = $this->repository->find($id);

        if ($note === null) {
            $this->notFound();
            return;
        }

        $this->render('notes/edit.php', [
            'title' => 'Редактировать заметку',
            'note' => $note,
            'errors' => [],
        ]);
    }

    // Обновление заметки.
    public function update(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $note = $this->repository->find($id);

        if ($note === null) {
            $this->notFound();
            return;
        }

        $data = $this->getFormData();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->render('notes/edit.php', [
                'title' => 'Редактировать заметку',
                'note' => array_merge($note, $data),
                'errors' => $errors,
            ]);
            return;
        }

        $this->repository->update($id, $data);
        $this->setFlashMessage('Заметка обновлена.');
        $this->redirect('/notes');
    }

    // Подтверждение удаления.
    public function delete(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $note = $this->repository->find($id);

        if ($note === null) {
            $this->notFound();
            return;
        }

        $this->render('notes/delete.php', [
            'title' => 'Удалить заметку',
            'note' => $note,
        ]);
    }

    // Удаление заметки.
    public function destroy(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $message = $this->repository->delete($id) ? 'Заметка удалена.' : 'Заметка не найдена.';

        $this->setFlashMessage($message);
        $this->redirect('/notes');
    }

    // Советы.
    public function tips(): void
    {
        $this->render('tips.php', ['title' => 'Советы']);
    }

    // О проекте.
    public function about(): void
    {
        $this->render('about.php', ['title' => 'О проекте']);
    }

    // Данные формы.
    private function getFormData(): array
    {
        return [
            'title' => trim($_POST['title'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'text' => trim($_POST['text'] ?? ''),
        ];
    }

    // Проверка формы.
    private function validate(array $data): array
    {
        $errors = [];

        if ($data['title'] === '') {
            $errors[] = 'Введите название заметки.';
        }

        if ($data['category'] === '') {
            $errors[] = 'Введите категорию.';
        }

        if ($data['text'] === '') {
            $errors[] = 'Введите текст заметки.';
        }

        return $errors;
    }

    // Уникальные категории.
    private function getCategories(array $notes): array
    {
        $categories = [];

        foreach ($notes as $note) {
            if (($note['category'] ?? '') !== '') {
                $categories[] = $note['category'];
            }
        }

        $categories = array_values(array_unique($categories));
        return $categories;
    }

    // Вывод шаблона.
    private function render(string $template, array $data = []): void
    {
        extract($data);

        ob_start();
        require BASE_PATH . '/templates/' . $template;
        $content = ob_get_clean();

        require BASE_PATH . '/templates/layout.php';
    }

    // Переход на страницу.
    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    // Одноразовое сообщение.
    private function setFlashMessage(string $message): void
    {
        $_SESSION['flash_message'] = $message;
    }

    // Получение сообщения.
    private function getFlashMessage(): string
    {
        $message = $_SESSION['flash_message'] ?? '';
        unset($_SESSION['flash_message']);

        return $message;
    }

    // Страница 404.
    private function notFound(): void
    {
        http_response_code(404);
        $this->render('not-found.php', ['title' => 'Страница не найдена']);
    }
}
