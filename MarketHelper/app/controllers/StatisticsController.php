<?php

require_once __DIR__ . '/../models/Statistics.php';
require_once __DIR__ . '/../models/Item.php';

class StatisticsController
{
    public function index()
    {
        $this->requireLogin();

        $itemId = isset($_GET['item_id']) && $_GET['item_id'] !== ''
            ? (int) $_GET['item_id']
            : null;

        $quality = $_GET['quality'] ?? 'both';

        if (!in_array($quality, ['both', 'HQ', 'NQ'], true)) {
            $quality = 'both';
        }

        $statisticsModel = new Statistics();
        $itemModel = new Item();

        $items = $itemModel->getAll();
        $stats = $statisticsModel->getStats((int) $_SESSION['user_id'], $itemId, $quality);
        $breakdown = $statisticsModel->getSoldItemsBreakdown((int) $_SESSION['user_id']);

        require_once __DIR__ . '/../views/statistics.php';
    }

    protected function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['error'][] = 'Pro zobrazení statistik musíte být přihlášen.';
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }
}