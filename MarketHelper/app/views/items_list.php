<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-3xl font-semibold text-[#1F2937]">
            Itemy
        </h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_URL ?>/index.php?url=item/create"
               class="bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                + Přidat item
            </a>
        <?php endif; ?>
    </div>

    <div class="bg-white/85 backdrop-blur-sm border border-[#FFE1E6] rounded-3xl overflow-hidden shadow-lg">
        <?php if (empty($items)): ?>
            <div class="p-10 text-center text-[#6B7280]">
                Zatím nejsou uložené žádné itemy.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#FFF5F7] text-[#FF7F96] text-sm border-b border-[#FFE1E6]">
                        <tr>
                            <th class="px-5 py-4">ID</th>
                            <th class="px-5 py-4">Název</th>
                            <th class="px-5 py-4">Kategorie</th>
                            <th class="px-5 py-4">Popis</th>
                            <th class="px-5 py-4">Vytvořil</th>
                            <th class="px-5 py-4">Akce</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#FFE1E6]">
                        <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-[#FFF5F7] transition">
                                <td class="px-5 py-4 text-[#6B7280]">
                                    <?= htmlspecialchars($item['id']) ?>
                                </td>

                                <td class="px-5 py-4 font-semibold text-[#FF7F96]">
                                    <?= htmlspecialchars($item['name']) ?>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="bg-[#E6D6F7] text-[#7C5FA8] px-3 py-1 rounded-full text-xs font-semibold">
                                        <?= htmlspecialchars($item['category'] ?? 'Other') ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-[#6B7280]">
                                    <?= htmlspecialchars($item['description'] ?? '—') ?>
                                </td>

                                <td class="px-5 py-4 text-[#6B7280]">
                                    <?= htmlspecialchars($item['created_by_username'] ?? '—') ?>
                                </td>

                                <td class="px-5 py-4">
                                    <a href="<?= BASE_URL ?>/index.php?url=item/show/<?= $item['id'] ?>"
                                       class="px-3 py-2 rounded-full bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] text-sm font-medium border border-[#FFB6C1] transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>