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

        <div class="mt-8 border-t border-[#FFE1E6] pt-6">
            <h3 class="text-2xl font-semibold text-[#1F2937] mb-4">
                Komentáře
            </h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="<?= BASE_URL ?>/index.php?url=comment/create" method="post" class="mb-6">
                    <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['id']) ?>">

                    <label for="content" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                        Přidat komentář
                    </label>

                    <textarea id="content"
                              name="content"
                              rows="4"
                              required
                              placeholder="Napište komentář k itemu..."
                              class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] placeholder-[#9CA3AF] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]"></textarea>

                    <button type="submit"
                            class="mt-3 bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                        Přidat komentář
                    </button>
                </form>
            <?php else: ?>
                <div class="mb-6 bg-[#FFF5F7] border border-[#FFE1E6] rounded-2xl p-4 text-[#6B7280]">
                    Pro přidání komentáře se musíte přihlásit.
                </div>
            <?php endif; ?>

            <?php if (empty($comments)): ?>
                <div class="bg-[#FFF5F7] border border-[#FFE1E6] rounded-2xl p-4 text-[#6B7280]">
                    Zatím zde nejsou žádné komentáře.
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-[#FFF5F7] border border-[#FFE1E6] rounded-2xl p-4">
                            <div class="flex justify-between items-start gap-4 mb-2">
                                <div>
                                    <p class="font-semibold text-[#1F2937]">
                                        <?= htmlspecialchars(!empty($comment['nickname']) ? $comment['nickname'] : $comment['username']) ?>
                                    </p>

                                    <p class="text-xs text-[#9CA3AF]">
                                        <?= htmlspecialchars($comment['created_at']) ?>

                                        <?php if (!empty($comment['updated_at']) && $comment['updated_at'] !== $comment['created_at']): ?>
                                            · upraveno <?= htmlspecialchars($comment['updated_at']) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <?php
                                    $canManageComment =
                                        isset($_SESSION['user_id']) &&
                                        (
                                            (int)$comment['user_id'] === (int)$_SESSION['user_id'] ||
                                            !empty($_SESSION['is_admin'])
                                        );
                                ?>

                                <?php if ($canManageComment): ?>
                                    <div class="flex gap-2">
                                        <button type="button"
                                                onclick="toggleEditComment(<?= (int)$comment['id'] ?>)"
                                                class="px-3 py-2 rounded-full bg-[#E6D6F7] hover:bg-[#d8c1f2] text-[#7C5FA8] text-sm font-medium border border-[#d8c1f2] transition">
                                            Upravit
                                        </button>

                                        <a href="<?= BASE_URL ?>/index.php?url=comment/delete/<?= $comment['id'] ?>"
                                        onclick="return confirm('Opravdu smazat tento komentář?')"
                                        class="px-3 py-2 rounded-full bg-white hover:bg-[#FFE1E6] text-[#FF7F96] text-sm font-medium border border-[#FFB6C1] transition">
                                            Smazat
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="text-[#1F2937]">
                                <?= nl2br(htmlspecialchars($comment['content'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>