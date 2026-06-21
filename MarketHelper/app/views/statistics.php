<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">

    <h2 class="text-3xl font-semibold text-[#1F2937] mb-6">
        Statistiky prodejů
    </h2>

    <form method="get" action="<?= BASE_URL ?>/index.php" class="bg-white/85 border border-[#FFE1E6] rounded-3xl shadow-lg p-6 mb-8">
        <input type="hidden" name="url" value="statistics/index">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block mb-2 text-sm font-medium text-[#FF7F96]">Item</label>
                <select name="item_id"
                        class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">
                    <option value="">Všechny itemy</option>

                    <?php foreach ($items as $item): ?>
                        <option value="<?= htmlspecialchars($item['id']) ?>"
                            <?= isset($_GET['item_id']) && (int)$_GET['item_id'] === (int)$item['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($item['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium text-[#FF7F96]">Kvalita</label>
                <select name="quality"
                        class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">
                    <option value="both" <?= ($_GET['quality'] ?? 'both') === 'both' ? 'selected' : '' ?>>HQ i NQ</option>
                    <option value="HQ" <?= ($_GET['quality'] ?? '') === 'HQ' ? 'selected' : '' ?>>HQ</option>
                    <option value="NQ" <?= ($_GET['quality'] ?? '') === 'NQ' ? 'selected' : '' ?>>NQ</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                    Filtrovat
                </button>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white/85 rounded-3xl border border-[#FFE1E6] p-6 shadow">
            <p class="text-[#6B7280] text-sm">Prodáno kusů</p>
            <p class="text-3xl font-semibold text-[#FF7F96]">
                <?= htmlspecialchars($stats['total_quantity'] ?? 0) ?>
            </p>
        </div>

        <div class="bg-white/85 rounded-3xl border border-[#FFE1E6] p-6 shadow">
            <p class="text-[#6B7280] text-sm">Celkový výdělek</p>
            <p class="text-3xl font-semibold text-emerald-600">
                <?= htmlspecialchars(number_format((float)($stats['total_earned'] ?? 0), 0, ',', ' ')) ?> gil
            </p>
        </div>

        <div class="bg-white/85 rounded-3xl border border-[#FFE1E6] p-6 shadow">
            <p class="text-[#6B7280] text-sm">Počet prodejů</p>
            <p class="text-3xl font-semibold text-[#FF7F96]">
                <?= htmlspecialchars($stats['sale_count'] ?? 0) ?>
            </p>
        </div>

        <div class="bg-white/85 rounded-3xl border border-[#FFE1E6] p-6 shadow">
            <p class="text-[#6B7280] text-sm">Průměrná cena / kus</p>
            <p class="text-3xl font-semibold text-[#7C5FA8]">
                <?= htmlspecialchars(number_format((float)($stats['average_price'] ?? 0), 0, ',', ' ')) ?> gil
            </p>
        </div>

        <div class="bg-white/85 rounded-3xl border border-[#FFE1E6] p-6 shadow">
            <p class="text-[#6B7280] text-sm">Nejvyšší cena / kus</p>
            <p class="text-3xl font-semibold text-[#FF7F96]">
                <?= htmlspecialchars(number_format((float)($stats['highest_price'] ?? 0), 0, ',', ' ')) ?> gil
            </p>
        </div>

        <div class="bg-white/85 rounded-3xl border border-[#FFE1E6] p-6 shadow">
            <p class="text-[#6B7280] text-sm">Nejnižší cena / kus</p>
            <p class="text-3xl font-semibold text-[#BE4257]">
                <?= htmlspecialchars(number_format((float)($stats['lowest_price'] ?? 0), 0, ',', ' ')) ?> gil
            </p>
        </div>
    </div>

    <h3 class="text-2xl font-semibold text-[#1F2937] mb-4">
        Rozpis podle itemů
    </h3>

    <div class="bg-white/85 border border-[#FFE1E6] rounded-3xl shadow-lg overflow-hidden">
        <?php if (empty($breakdown)): ?>
            <div class="p-10 text-center text-[#6B7280]">
                Zatím nejsou žádné prodané itemy.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#FFF5F7] text-[#FF7F96] text-sm border-b border-[#FFE1E6]">
                        <tr>
                            <th class="px-5 py-4">Item</th>
                            <th class="px-5 py-4">Kvalita</th>
                            <th class="px-5 py-4">Prodáno kusů</th>
                            <th class="px-5 py-4">Průměrná cena</th>
                            <th class="px-5 py-4">Celkem vyděláno</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#FFE1E6]">
                        <?php foreach ($breakdown as $row): ?>
                            <tr class="hover:bg-[#FFF5F7] transition">
                                <td class="px-5 py-4 font-semibold text-[#FF7F96]">
                                    <?= htmlspecialchars($row['item_name'] ?? 'Neznámý item') ?>
                                </td>

                                <td class="px-5 py-4">
                                    <?php if (($row['quality'] ?? 'NQ') === 'HQ'): ?>
                                        <span class="bg-[#FFE1E6] text-[#FF7F96] px-3 py-1 rounded-full text-xs font-semibold border border-[#FFB6C1]">
                                            HQ
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-[#F3F4F6] text-[#6B7280] px-3 py-1 rounded-full text-xs font-semibold border border-[#E5E7EB]">
                                            NQ
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-4 text-[#1F2937]">
                                    <?= htmlspecialchars($row['total_quantity']) ?>
                                </td>

                                <td class="px-5 py-4 text-[#6B7280]">
                                    <?= htmlspecialchars(number_format((float)$row['average_price'], 0, ',', ' ')) ?> gil
                                </td>

                                <td class="px-5 py-4 font-semibold text-emerald-600">
                                    <?= htmlspecialchars(number_format((float)$row['total_earned'], 0, ',', ' ')) ?> gil
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