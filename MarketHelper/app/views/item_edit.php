<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="max-w-3xl mx-auto bg-white/85 backdrop-blur-sm border border-[#FFE1E6] rounded-3xl shadow-lg p-8">

        <h2 class="text-3xl font-semibold text-[#1F2937] mb-2">
            Upravit item
        </h2>

        <p class="text-[#6B7280] mb-8">
            Upravujete item:
            <span class="font-semibold text-[#FF7F96]">
                <?= htmlspecialchars($item['name']) ?>
            </span>
        </p>

        <form action="<?= BASE_URL ?>/index.php?url=item/update/<?= htmlspecialchars($item['id']) ?>" method="post" class="space-y-6">

            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Název itemu *
                </label>

                <input type="text"
                       id="name"
                       name="name"
                       required
                       value="<?= htmlspecialchars($item['name']) ?>"
                       class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">
            </div>

            <div>
                <label for="category" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Kategorie
                </label>

                <select id="category"
                        name="category"
                        class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">

                    <?php
                        $categories = ['Material', 'Gear', 'Consumable', 'Housing', 'Other'];
                        $currentCategory = $item['category'] ?? 'Other';
                    ?>

                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"
                            <?= $currentCategory === $category ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Popis
                </label>

                <textarea id="description"
                          name="description"
                          rows="5"
                          class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                        class="bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                    Uložit změny
                </button>

                <a href="<?= BASE_URL ?>/index.php?url=item/show/<?= htmlspecialchars($item['id']) ?>"
                   class="bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] border border-[#FFE1E6] px-5 py-3 rounded-full shadow-sm font-medium transition">
                    Zrušit
                </a>
            </div>

        </form>
    </div>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>