<?php require_once __DIR__ . '/../layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">

    <h2 class="text-3xl font-semibold text-[#1F2937] mb-6">
        Admin panel
    </h2>

    <div class="bg-white/85 border border-[#FFE1E6] rounded-3xl shadow-lg overflow-hidden mb-10">
        <div class="p-6 border-b border-[#FFE1E6]">
            <h3 class="text-2xl font-semibold text-[#FF7F96]">
                Uživatelé
            </h3>
            <p class="text-[#6B7280] text-sm mt-1">
                Mazat lze pouze uživatele bez admin práv.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#FFF5F7] text-[#FF7F96] text-sm">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Username</th>
                        <th class="px-5 py-4">E-mail</th>
                        <th class="px-5 py-4">Role</th>
                        <th class="px-5 py-4">Akce</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#FFE1E6]">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-[#FFF5F7] transition">
                            <td class="px-5 py-4 text-[#6B7280]">
                                <?= htmlspecialchars($user['id']) ?>
                            </td>

                            <td class="px-5 py-4 font-semibold text-[#1F2937]">
                                <?= htmlspecialchars($user['username']) ?>
                            </td>

                            <td class="px-5 py-4 text-[#6B7280]">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>

                            <td class="px-5 py-4">
                                <?php if (!empty($user['is_admin'])): ?>
                                    <span class="bg-[#FFF5F7] text-[#FF7F96] text-xs font-semibold px-3 py-1 rounded-full border border-[#FFB6C1]">
                                        ADMIN
                                    </span>
                                <?php else: ?>
                                    <span class="bg-[#F3F4F6] text-[#6B7280] text-xs font-semibold px-3 py-1 rounded-full border border-[#E5E7EB]">
                                        USER
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-5 py-4">
                                <?php if (empty($user['is_admin']) && (int)$user['id'] !== (int)$_SESSION['user_id']): ?>
                                    <a href="<?= BASE_URL ?>/index.php?url=auth/deleteUser/<?= $user['id'] ?>"
                                       onclick="return confirm('Opravdu smazat tohoto uživatele?')"
                                       class="px-3 py-2 rounded-full bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] text-sm font-medium border border-[#FFB6C1] transition">
                                        Smazat
                                    </a>
                                <?php else: ?>
                                    <span class="text-[#9CA3AF] text-sm">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

    <div class="bg-white/85 border border-[#FFE1E6] rounded-3xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-[#FFE1E6]">
            <h3 class="text-2xl font-semibold text-[#FF7F96]">
                Itemy
            </h3>
            <p class="text-[#6B7280] text-sm mt-1">
                Admin může mazat itemy ze systému.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#FFF5F7] text-[#FF7F96] text-sm">
                    <tr>
                        <th class="px-5 py-4">ID</th>
                        <th class="px-5 py-4">Název</th>
                        <th class="px-5 py-4">Kategorie</th>
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

                            <td class="px-5 py-4 text-[#6B7280]">
                                <?= htmlspecialchars($item['category'] ?? 'Other') ?>
                            </td>

                            <td class="px-5 py-4 text-[#6B7280]">
                                <?= htmlspecialchars($item['created_by_username'] ?? '—') ?>
                            </td>

                            <td class="px-5 py-4">
                                <a href="<?= BASE_URL ?>/index.php?url=item/delete/<?= $item['id'] ?>"
                                   onclick="return confirm('Opravdu smazat tento item?')"
                                   class="px-3 py-2 rounded-full bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] text-sm font-medium border border-[#FFB6C1] transition">
                                    Smazat
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>