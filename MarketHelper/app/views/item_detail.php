<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
    <div class="max-w-3xl mx-auto bg-white/85 backdrop-blur-sm border border-[#FFE1E6] rounded-3xl shadow-lg p-8">

        <div class="flex justify-between items-start gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-semibold text-[#1F2937]">
                    <?= htmlspecialchars($item['name']) ?>
                </h2>

                <p class="text-[#6B7280] mt-2">
                    Detail itemu
                </p>
            </div>

            <a href="<?= BASE_URL ?>/index.php?url=item/index"
               class="bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] border border-[#FFE1E6] px-5 py-3 rounded-full shadow-sm font-medium transition">
                Zpět
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-[#FFF5F7] rounded-2xl p-4 border border-[#FFE1E6]">
                <p class="text-sm text-[#6B7280]">ID</p>
                <p class="font-semibold text-[#1F2937]">
                    <?= htmlspecialchars($item['id']) ?>
                </p>
            </div>

            <div class="bg-[#FFF5F7] rounded-2xl p-4 border border-[#FFE1E6]">
                <p class="text-sm text-[#6B7280]">Kategorie</p>
                <p class="font-semibold text-[#FF7F96]">
                    <?= htmlspecialchars($item['category'] ?? 'Other') ?>
                </p>
            </div>

            <div class="bg-[#FFF5F7] rounded-2xl p-4 border border-[#FFE1E6]">
                <p class="text-sm text-[#6B7280]">Vytvořil</p>
                <p class="font-semibold text-[#1F2937]">
                    <?= htmlspecialchars($item['created_by_username'] ?? '—') ?>
                </p>
            </div>

            <div class="bg-[#FFF5F7] rounded-2xl p-4 border border-[#FFE1E6]">
                <p class="text-sm text-[#6B7280]">Vytvořeno</p>
                <p class="font-semibold text-[#1F2937]">
                    <?= htmlspecialchars($item['created_at'] ?? '—') ?>
                </p>
            </div>
        </div>

        <div class="mt-6 bg-[#FFF5F7] rounded-2xl p-4 border border-[#FFE1E6]">
            <p class="text-sm text-[#6B7280] mb-2">Popis</p>

            <div class="text-[#1F2937]">
                <?= !empty($item['description'])
                    ? nl2br(htmlspecialchars($item['description']))
                    : 'Bez popisu.' ?>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>