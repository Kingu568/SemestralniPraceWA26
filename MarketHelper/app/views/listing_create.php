<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="max-w-3xl mx-auto bg-white/85 backdrop-blur-sm border border-[#FFE1E6] rounded-3xl shadow-lg p-8">

        <h2 class="text-3xl font-semibold text-[#1F2937] mb-2">
            Přidat aktivní prodej
        </h2>

        <p class="text-[#6B7280] mb-8">
            Přidejte nový aktivní listing na Market Board.
        </p>

        <form action="<?= BASE_URL ?>/index.php?url=listing/store" method="post" class="space-y-6">

            <div>
                <label for="item_id" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Item
                </label>

                <div class="flex gap-2">
                    <select id="item_id" name="item_id" required
                            class="flex-1 rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">
                        <option value="">Vyber item</option>

                        <?php foreach ($items as $item): ?>
                            <option value="<?= htmlspecialchars($item['id']) ?>">
                                <?= htmlspecialchars($item['name']) ?>
                                <?= !empty($item['category']) ? ' — ' . htmlspecialchars($item['category']) : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <a href="<?= BASE_URL ?>/index.php?url=item/create"
                       class="bg-[#E6D6F7] hover:bg-[#d8c1f2] text-[#7C5FA8] px-5 py-3 rounded-2xl font-medium transition whitespace-nowrap">
                        + Item
                    </a>
                </div>
            </div>

            <div>
                <label for="quantity" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Množství
                </label>

                <input type="number"
                       id="quantity"
                       name="quantity"
                       min="1"
                       max="99"
                       required
                       class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">
            </div>

            <div>
                <label for="price_per_unit" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Cena za kus
                </label>

                <input type="number"
                       id="price_per_unit"
                       name="price_per_unit"
                       min="1"
                       step="1"
                       required
                       class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">
            </div>

            <div>
                <label for="quality" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Kvalita
                </label>

                <select id="quality" name="quality" required
                        class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">

                    <option value="NQ">
                        NQ - Normal Quality
                    </option>

                    <option value="HQ">
                        HQ - High Quality
                    </option>

                </select>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                        class="bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                    Uložit prodej
                </button>

                <a href="<?= BASE_URL ?>/index.php"
                   class="bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] border border-[#FFE1E6] px-5 py-3 rounded-full shadow-sm font-medium transition">
                    Zrušit
                </a>
            </div>

        </form>
    </div>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>