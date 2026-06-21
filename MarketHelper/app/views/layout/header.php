<!DOCTYPE html>
<html lang="cs" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/img/logo.png">
    <title>FFXIV Market Helper</title>
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-br from-[#FFF5F7] via-[#FFE1E6] to-[#E6D6F7] text-[#1F2937]">

<header class="bg-white/80 backdrop-blur-md border-b border-[#FFE1E6] shadow-sm">
    <div class="container mx-auto px-6 py-5 flex flex-col md:flex-row justify-between items-center gap-4">

        <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-4">
            <img src="<?= BASE_URL ?>/img/logo.png"
                 alt="FFXIV Market Helper logo"
                 class="w-16 h-16 object-contain rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] shadow-sm">

            <div>
                <h1 class="text-3xl font-bold tracking-wide text-[#1F2937]">
                    FFXIV <span class="text-[#FF8FA3]">Market Helper</span>
                </h1>
                <p class="text-sm text-[#6B7280]">
                    Your personal market companion.
                </p>
            </div>
        </a>

        <nav>
            <ul class="flex flex-wrap gap-3 items-center">

                <li>
                    <a href="<?= BASE_URL ?>/index.php"
                       class="inline-block rounded-full bg-[#FFF5F7] px-5 py-2.5 text-[#FF7F96] font-medium shadow-sm border border-[#FFE1E6] hover:bg-[#FFE1E6] transition">
                        Aktivní prodeje
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/index.php?url=item/index"
                       class="inline-block rounded-full bg-[#FFF5F7] px-5 py-2.5 text-[#FF7F96] font-medium shadow-sm border border-[#FFE1E6] hover:bg-[#FFE1E6] transition">
                        Itemy
                    </a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=listing/create"
                           class="inline-block rounded-full bg-[#FFB6C1] px-5 py-2.5 text-white font-semibold shadow-sm hover:bg-[#FF8FA3] transition">
                            + Přidat prodej
                        </a>
                    </li>

                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=statistics/index"
                           class="inline-block rounded-full bg-[#E6D6F7] px-5 py-2.5 text-[#7C5FA8] font-medium shadow-sm hover:bg-[#d8c1f2] transition">
                            Statistiky
                        </a>
                    </li>

                    <li class="text-[#6B7280] text-sm flex items-center gap-2">
                        Ahoj,
                        <span class="font-semibold text-[#1F2937]">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </span>

                    <?php if (!empty($_SESSION['is_admin'])): ?>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/admin"
                        class="bg-[#FFF5F7] hover:bg-[#FFE1E6] text-[#FF7F96] text-xs font-semibold px-3 py-1 rounded-full border border-[#FFB6C1] transition">
                            ADMIN
                        </a>
                    <?php endif; ?>
                    </li>

                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/logout"
                           class="inline-block rounded-full bg-white px-5 py-2.5 text-[#FF7F96] font-medium shadow-sm border border-[#FFB6C1] hover:bg-[#FFF5F7] transition">
                            Odhlásit
                        </a>
                    </li>

                <?php else: ?>

                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/login"
                           class="inline-block rounded-full bg-[#FFF5F7] px-5 py-2.5 text-[#FF7F96] font-medium shadow-sm border border-[#FFE1E6] hover:bg-[#FFE1E6] transition">
                            Přihlásit
                        </a>
                    </li>

                    <li>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/register"
                           class="inline-block rounded-full bg-[#FFB6C1] px-5 py-2.5 text-white font-semibold shadow-sm hover:bg-[#FF8FA3] transition">
                            Registrace
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </nav>

    </div>
</header>

<div class="container mx-auto px-6 pt-8">

    <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>

        <div class="space-y-3">

            <?php foreach ($_SESSION['messages'] as $type => $messages): ?>

                <?php
                $styles = [
                    'success' => 'bg-[#FDF2F8] border-[#D8B4FE] text-[#7C3AED]',
                    'error'   => 'bg-[#FFF5F7] border-[#FF8FA3] text-[#BE4257]',
                    'notice'  => 'bg-[#FFF7ED] border-[#F9A8D4] text-[#BE185D]'
                ];

                $style = $styles[$type] ?? 'bg-white border-[#FFE1E6] text-[#1F2937]';
                ?>

                <?php foreach ($messages as $message): ?>

                    <div class="<?= $style ?> border-l-4 p-4 rounded-2xl shadow-sm notification">
                        <div class="flex justify-between items-center">
                            <p class="font-medium text-sm">
                                <?= htmlspecialchars($message) ?>
                            </p>

                            <button class="close-notification ml-4 font-bold opacity-70 hover:opacity-100">
                                ×
                            </button>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endforeach; ?>

            <?php unset($_SESSION['messages']); ?>

        </div>

    <?php endif; ?>

</div>