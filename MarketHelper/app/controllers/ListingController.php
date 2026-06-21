<?php

require_once __DIR__ . '/../models/Listing.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../dto/ListingDTO.php';

class ListingController
{
    public function index()
    {
        $listingModel = new Listing();
        $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
        $listings = $listingModel->getActive($userId);

        require_once __DIR__ . '/../views/listings_list.php';
    }

    public function create()
    {
        $this->requireLogin();

        $itemModel = new Item();
        $items = $itemModel->getAll();

        require_once __DIR__ . '/../views/listing_create.php';
    }

    public function store()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->addNoticeMessage('Formulář nebyl odeslán.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $quantity = (int) ($_POST['quantity'] ?? 0);

        if ($quantity < 1 || $quantity > 99) {
            $this->addErrorMessage('Počet kusů musí být mezi 1 a 99.');
            header('Location: ' . BASE_URL . '/index.php?url=listing/create');
            exit;
        }

        $listingData = new ListingDTO([
            'item_id' => $_POST['item_id'] ?? 0,
            'quality' => $_POST['quality'] ?? 'NQ',
            'quantity' => $quantity,
            'price_per_unit' => $_POST['price_per_unit'] ?? 0,
            'status' => 'active'
        ]);

        if ($listingData->item_id <= 0 || $listingData->price_per_unit <= 0) {
            $this->addErrorMessage('Vyberte item a zadejte platnou cenu.');
            header('Location: ' . BASE_URL . '/index.php?url=listing/create');
            exit;
        }

        $listingModel = new Listing();
        $isCreated = $listingModel->create($listingData, (int)$_SESSION['user_id']);

        if ($isCreated) {
            $this->addSuccessMessage('Prodej byl úspěšně přidán.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $this->addErrorMessage('Prodej se nepodařilo uložit.');
        header('Location: ' . BASE_URL . '/index.php?url=listing/create');
        exit;
    }
    public function edit($id = null)
    {
        $this->requireLogin();

        if (!$id) {
            $this->addErrorMessage('Nebyl zadán listing k úpravě.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $listingModel = new Listing();
        $listing = $listingModel->getById($id);

        if (!$listing) {
            $this->addErrorMessage('Listing nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ((int)$listing['created_by'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento listing.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $itemModel = new Item();
        $items = $itemModel->getAll();

        require_once __DIR__ . '/../views/listing_edit.php';
    }

    public function update($id = null)
    {
        $this->requireLogin();

        if (!$id) {
            $this->addErrorMessage('Nebyl zadán listing k aktualizaci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->addNoticeMessage('Pro úpravu listingu je nutné odeslat formulář.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $listingModel = new Listing();
        $existingListing = $listingModel->getById($id);

        if (!$existingListing) {
            $this->addErrorMessage('Listing nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ((int)$existingListing['created_by'] !== (int)$_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento listing.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $quantity = (int)($_POST['quantity'] ?? 0);

        if ($quantity < 1 || $quantity > 99) {
            $this->addErrorMessage('Počet kusů musí být mezi 1 a 99.');
            header('Location: ' . BASE_URL . '/index.php?url=listing/edit/' . $id);
            exit;
        }

        $listingData = new ListingDTO([
            'item_id' => $_POST['item_id'] ?? 0,
            'quality' => $_POST['quality'] ?? 'NQ',
            'quantity' => $quantity,
            'price_per_unit' => $_POST['price_per_unit'] ?? 0,
            'status' => 'active'
        ]);

        if ($listingData->item_id <= 0 || $listingData->price_per_unit <= 0) {
            $this->addErrorMessage('Vyberte item a zadejte platnou cenu.');
            header('Location: ' . BASE_URL . '/index.php?url=listing/edit/' . $id);
            exit;
        }

        $isUpdated = $listingModel->update($id, $listingData, (int)$_SESSION['user_id']);

        if ($isUpdated) {
            $this->addSuccessMessage('Listing byl úspěšně upraven.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $this->addErrorMessage('Listing se nepodařilo upravit.');
        header('Location: ' . BASE_URL . '/index.php?url=listing/edit/' . $id);
        exit;
    }

    public function sold($id = null)
    {
        $this->requireLogin();

        if (!$id) {
            $this->addErrorMessage('Nebyl zadán listing k označení jako prodaný.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $listingModel = new Listing();
        $isSold = $listingModel->markAsSold($id, (int) $_SESSION['user_id']);

        if ($isSold) {
            $this->addSuccessMessage('Listing byl označen jako prodaný a uložen do statistik.');
        } else {
            $this->addErrorMessage('Listing se nepodařilo označit jako prodaný.');
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    public function delete($id = null)
    {
        $this->requireLogin();

        if (!$id) {
            $this->addErrorMessage('Nebyl zadán listing ke smazání.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $listingModel = new Listing();
        $isDeleted = $listingModel->delete($id);

        if ($isDeleted) {
            $this->addSuccessMessage('Listing byl smazán bez uložení do statistik.');
        } else {
            $this->addErrorMessage('Listing se nepodařilo smazat.');
        }

        header('Location: ' . BASE_URL . '/index.php');
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

    protected function addNoticeMessage($message)
    {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message)
    {
        $_SESSION['messages']['error'][] = $message;
    }
}