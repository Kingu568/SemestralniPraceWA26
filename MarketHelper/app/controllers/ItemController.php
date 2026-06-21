<?php

require_once __DIR__ . '/../models/Item.php';

class ItemController
{
    public function index()
    {
        $itemModel = new Item();
        $items = $itemModel->getAll();

        require_once __DIR__ . '/../views/items_list.php';
    }
    public function show($id = null)
    {
        if (!$id) {
            $this->addErrorMessage('Nebyl zadán item.');
            header('Location: ' . BASE_URL . '/index.php?url=item/index');
            exit;
        }

        $itemModel = new Item();
        $item = $itemModel->getById($id);

        if (!$item) {
            $this->addErrorMessage('Item nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php?url=item/index');
            exit;
        }

        require_once __DIR__ . '/../views/item_detail.php';
    }

    public function create()
    {
        $this->requireLogin();

        require_once __DIR__ . '/../views/item_create.php';
    }

    public function store()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->addNoticeMessage('Formulář nebyl odeslán.');
            header('Location: ' . BASE_URL . '/index.php?url=item/create');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '') {
            $this->addErrorMessage('Název itemu je povinný.');
            header('Location: ' . BASE_URL . '/index.php?url=item/create');
            exit;
        }

        $itemModel = new Item();
        $isCreated = $itemModel->create($name, $category, $description, (int) $_SESSION['user_id']);

        if ($isCreated) {
            $this->addSuccessMessage('Item byl úspěšně vytvořen.');
            header('Location: ' . BASE_URL . '/index.php?url=item/index');
            exit;
        }

        $this->addErrorMessage('Item se nepodařilo vytvořit.');
        header('Location: ' . BASE_URL . '/index.php?url=item/create');
        exit;
    }

    public function delete($id = null)
    {
        $this->requireAdmin();

        if (!$id) {
            $this->addErrorMessage('Nebyl zadán item ke smazání.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/admin');
            exit;
        }

        $itemModel = new Item();
        $isDeleted = $itemModel->delete((int) $id);

        if ($isDeleted) {
            $this->addSuccessMessage('Item byl smazán.');
        } else {
            $this->addErrorMessage('Item se nepodařilo smazat.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=auth/admin');
        exit;
    }

    protected function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro tuto akci musíte být přihlášen.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    protected function requireAdmin()
    {
        if (empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění pro tuto akci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    protected function addSuccessMessage($message)
    {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message)
    {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message)
    {
        $_SESSION['messages']['error'][] = $message;
    }
}