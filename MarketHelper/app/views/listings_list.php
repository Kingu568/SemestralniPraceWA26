<?php require_once __DIR__ . '/layout/header.php'; ?>

<?php
function listingAge($createdAt)
{
    if (empty($createdAt)) {
        return '—';
    }

    $created = new DateTime($createdAt);
    $now = new DateTime();

    $diffSeconds = $now->getTimestamp() - $created->getTimestamp();

    if ($diffSeconds < 60) {
        return 'just now';
    }

    $minutes = floor($diffSeconds / 60);

    if ($minutes < 60) {
        return $minutes . ' m old';
    }

    $hours = floor($minutes / 60);

    if ($hours < 24) {
        return $hours . ' h old';
    }

    $days = floor($hours / 24);

    return $days . ' days old';
}
?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <h2 class="text-3xl font-semibold text-[#1F2937] mb-6">
        Aktivní prodeje
    </h2>

    <div class="bg-white/85 border border-[#FFE1E6] rounded-3xl shadow-lg overflow-hidden">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="p-10 text-center text-[#6B7280]">
                <p class="mb-4 font-medium">
                    Nejste přihlášený. Pro zobrazení svých listingů se prosím přihlaste nebo zaregistrujte.
                </p>

                <div class="flex justify-center gap-3">
                    <a href="<?= BASE_URL ?>/index.php?url=auth/login"
                       class="bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] border border-[#FFE1E6] px-5 py-3 rounded-full shadow-sm font-medium transition">
                        Přihlásit se
                    </a>

                    <a href="<?= BASE_URL ?>/index.php?url=auth/register"
                       class="bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                        Zaregistrovat se
                    </a>
                </div>
            </div>

        <?php elseif (empty($listings)): ?>
            <div class="p-10 text-center text-[#6B7280]">
                <p class="mb-4 font-medium">
                    Zatím nemáte žádné aktivní listingy.
                </p>

                <a href="<?= BASE_URL ?>/index.php?url=listing/create"
                   class="inline-block bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                    + Vytvořit listing
                </a>
            </div>

        <?php else: ?>
            <table class="w-full text-left">
                <thead class="bg-[#FFF5F7] text-[#FF7F96] text-sm border-b border-[#FFE1E6]">
                    <tr>
                        <th class="px-5 py-4">Item</th>
                        <th class="px-5 py-4">Množství</th>
                        <th class="px-5 py-4">Cena za kus</th>
                        <th class="px-5 py-4">Kvalita</th>
                        <th class="px-5 py-4">Celkem</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Stáří</th>
                        <th class="px-5 py-4">Akce</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#FFE1E6] text-[#1F2937]">
                    <?php foreach ($listings as $listing): ?>
                        <tr class="hover:bg-[#FFF5F7] transition">
                            <td class="px-5 py-4 font-semibold text-[#FF7F96]">
                                <?= htmlspecialchars($listing['item_name'] ?? 'Neznámý item') ?>
                            </td>

                            <td class="px-5 py-4">
                                <?= htmlspecialchars($listing['quantity']) ?>
                            </td>

                            <td class="px-5 py-4 text-[#6B7280]">
                                <?= htmlspecialchars(number_format((float)$listing['price_per_unit'], 0, ',', ' ')) ?> gil
                            </td>

                            <td class="px-5 py-4">
                                <?php if (($listing['quality'] ?? 'NQ') === 'HQ'): ?>
                                    <span class="bg-[#FFE1E6] text-[#FF7F96] px-3 py-1 rounded-full text-xs font-semibold border border-[#FFB6C1]">
                                        HQ
                                    </span>
                                <?php else: ?>
                                    <span class="bg-[#F3F4F6] text-[#6B7280] px-3 py-1 rounded-full text-xs font-semibold border border-[#E5E7EB]">
                                        NQ
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-5 py-4 font-semibold text-[#1F2937]">
                                <?= htmlspecialchars(number_format((float)($listing['quantity'] * $listing['price_per_unit']), 0, ',', ' ')) ?> gil
                            </td>

                            <td class="px-5 py-4">
                                <span class="bg-[#E6D6F7] text-[#7C5FA8] px-3 py-1 rounded-full text-xs font-semibold">
                                    <?= htmlspecialchars($listing['status']) ?>
                                </span>
                            </td>

                            <td class="px-5 py-4 text-[#6B7280]">
                                <?= htmlspecialchars(listingAge($listing['updated_at'] ?? null)) ?>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <a href="<?= BASE_URL ?>/index.php?url=listing/sold/<?= $listing['id'] ?>"
                                           onclick="return confirm('Označit tento listing jako prodaný?')"
                                           class="px-3 py-2 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium border border-emerald-200 transition">
                                            SOLD
                                        </a>

                                        <a href="<?= BASE_URL ?>/index.php?url=listing/edit/<?= $listing['id'] ?>"
                                           class="px-3 py-2 rounded-full bg-[#E6D6F7] hover:bg-[#d8c1f2] text-[#7C5FA8] text-sm font-medium border border-[#d8c1f2] transition">
                                            EDIT
                                        </a>

                                        <a href="<?= BASE_URL ?>/index.php?url=listing/delete/<?= $listing['id'] ?>"
                                           onclick="return confirm('Smazat listing bez uložení do statistik?')"
                                           class="px-3 py-2 rounded-full bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] text-sm font-medium border border-[#FFB6C1] transition">
                                            DELETE
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>