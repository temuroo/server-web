<?php
// Защита от прямого открытия.
if (!defined('APP_STARTED')) {
    exit('Доступ запрещён.');
}

// Работа с JSON-файлом заметок.
class NoteRepository
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    // Все заметки.
    public function all(): array
    {
        $json = file_get_contents($this->filePath);
        $notes = json_decode($json ?: '[]', true);

        return is_array($notes) ? $notes : [];
    }

    // Поиск по id.
    public function find(int $id): ?array
    {
        foreach ($this->all() as $note) {
            if ((int)$note['id'] === $id) {
                return $note;
            }
        }

        return null;
    }

    // Добавление заметки.
    public function create(array $data): void
    {
        $notes = $this->all();
        $notes[] = [
            'id' => $this->nextId($notes),
            'title' => $data['title'],
            'category' => $data['category'],
            'text' => $data['text'],
        ];

        $this->save($notes);
    }

    // Обновление заметки.
    public function update(int $id, array $data): bool
    {
        $notes = $this->all();

        foreach ($notes as &$note) {
            if ((int)$note['id'] === $id) {
                $note['title'] = $data['title'];
                $note['category'] = $data['category'];
                $note['text'] = $data['text'];
                $this->save($notes);
                return true;
            }
        }

        return false;
    }

    // Удаление заметки.
    public function delete(int $id): bool
    {
        $notes = $this->all();
        $filteredNotes = [];
        $deleted = false;

        foreach ($notes as $note) {
            if ((int)$note['id'] === $id) {
                $deleted = true;
                continue;
            }

            $filteredNotes[] = $note;
        }

        if ($deleted) {
            $this->save($filteredNotes);
        }

        return $deleted;
    }

    // Сохранение в JSON.
    private function save(array $notes): void
    {
        file_put_contents(
            $this->filePath,
            json_encode(array_values($notes), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    // Новый id.
    private function nextId(array $notes): int
    {
        $maxId = 0;

        foreach ($notes as $note) {
            $maxId = max($maxId, (int)$note['id']);
        }

        return $maxId + 1;
    }
}
