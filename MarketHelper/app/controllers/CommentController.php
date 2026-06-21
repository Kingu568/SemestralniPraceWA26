<?php

require_once __DIR__ . '/../models/Comment.php';

class CommentController
{
    public function create()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $itemId = (int)($_POST['item_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if ($itemId <= 0 || $content === '') {
            $this->addErrorMessage('Komentář nesmí být prázdný.');
            header('Location: ' . BASE_URL . '/index.php?url=item/show/' . $itemId);
            exit;
        }

        $commentModel = new Comment();

        $isCreated = $commentModel->create(
            $itemId,
            (int)$_SESSION['user_id'],
            $content
        );

        if ($isCreated) {
            $this->addSuccessMessage('Komentář byl přidán.');
        } else {
            $this->addErrorMessage('Komentář se nepodařilo uložit.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=item/show/' . $itemId);
        exit;
    }

    public function delete($id = null)
    {
        $this->requireLogin();

        if (!$id) {
            $this->addErrorMessage('Komentář nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $commentModel = new Comment();

        $comment = $commentModel->getById((int)$id);

        if (!$comment) {
            $this->addErrorMessage('Komentář neexistuje.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isOwner =
            (int)$comment['user_id'] === (int)$_SESSION['user_id'];

        $isAdmin =
            !empty($_SESSION['is_admin']);

        if (!$isOwner && !$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění mazat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $commentModel->delete((int)$id);

        $this->addSuccessMessage('Komentář byl smazán.');

        header(
            'Location: ' .
            BASE_URL .
            '/index.php?url=item/show/' .
            $comment['item_id']
        );

        exit;
    }
    public function update($id = null)
    {
        $this->requireLogin();

        if (!$id) {
            $this->addErrorMessage('Komentář nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->addErrorMessage('Pro úpravu komentáře je nutné odeslat formulář.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $commentModel = new Comment();
        $comment = $commentModel->getById((int)$id);

        if (!$comment) {
            $this->addErrorMessage('Komentář neexistuje.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isOwner = (int)$comment['user_id'] === (int)$_SESSION['user_id'];
        $isAdmin = !empty($_SESSION['is_admin']);

        if (!$isOwner && !$isAdmin) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php?url=item/show/' . $comment['item_id']);
            exit;
        }

        $content = trim($_POST['content'] ?? '');

        if ($content === '') {
            $this->addErrorMessage('Komentář nesmí být prázdný.');
            header('Location: ' . BASE_URL . '/index.php?url=item/show/' . $comment['item_id']);
            exit;
        }

        $isUpdated = $commentModel->update((int)$id, $content);

        if ($isUpdated) {
            $this->addSuccessMessage('Komentář byl upraven.');
        } else {
            $this->addErrorMessage('Komentář se nepodařilo upravit.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=item/show/' . $comment['item_id']);
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

    protected function addSuccessMessage($message)
    {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addErrorMessage($message)
    {
        $_SESSION['messages']['error'][] = $message;
    }
}