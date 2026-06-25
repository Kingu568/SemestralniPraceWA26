<?php

require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Item.php';

class AuthController
{
    public function register()
    {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function storeUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        $username = htmlspecialchars(trim($_POST['username'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $nickname = htmlspecialchars(trim($_POST['nickname'] ?? ''));

        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            $this->addErrorMessage('Vyplňte prosím všechna povinná pole.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $this->addErrorMessage('Zadaná hesla se neshodují.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        
        if (strlen($password) < 8) {
            $this->addErrorMessage('Heslo musí mít alespoň 8 znaků.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        if (!preg_match('/[0-9]/', $password)) {
            $this->addErrorMessage('Heslo musí obsahovat alespoň jedno číslo.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }
        

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->register($username, $email, $password, $nickname)) {
            $this->addSuccessMessage('Registrace byla úspěšná. Nyní se můžete přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $this->addErrorMessage('Uživatel s tímto e-mailem již existuje.');
        header('Location: ' . BASE_URL . '/index.php?url=auth/register');
        exit;
    }

    public function login()
    {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = !empty($user['nickname'])
                ? $user['nickname']
                : $user['username'];

            $_SESSION['is_admin'] = !empty($user['is_admin']) ? 1 : 0;

            $this->addSuccessMessage('Vítejte zpět, ' . $_SESSION['user_name'] . '!');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $this->addErrorMessage('Nesprávný e-mail nebo heslo.');
        header('Location: ' . BASE_URL . '/index.php?url=auth/login');
        exit;
    }

    public function admin()
    {
        $this->requireAdmin();

        $db = (new Database())->getConnection();

        $userModel = new User($db);
        $itemModel = new Item();

        $users = $userModel->getAll();
        $items = $itemModel->getAll();

        require_once __DIR__ . '/../views/auth/admin.php';
    }

    public function deleteUser($id = null)
    {
        $this->requireAdmin();

        if (!$id) {
            $this->addErrorMessage('Nebyl zadán uživatel ke smazání.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/admin');
            exit;
        }

        if ((int)$id === (int)$_SESSION['user_id']) {
            $this->addErrorMessage('Nemůžete smazat vlastní účet.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/admin');
            exit;
        }

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        $user = $userModel->findById((int)$id);

        if (!$user) {
            $this->addErrorMessage('Uživatel nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/admin');
            exit;
        }

        if (!empty($user['is_admin'])) {
            $this->addErrorMessage('Nelze smazat uživatele s admin právy.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/admin');
            exit;
        }

        $isDeleted = $userModel->deleteNonAdmin((int)$id);

        if ($isDeleted) {
            $this->addSuccessMessage('Uživatel byl smazán.');
        } else {
            $this->addErrorMessage('Uživatele se nepodařilo smazat.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=auth/admin');
        exit;
    }

    public function logout()
    {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['is_admin']);

        $this->addSuccessMessage('Byli jste úspěšně odhlášeni.');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    protected function requireAdmin()
    {
        if (empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění pro přístup do administrace.');
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