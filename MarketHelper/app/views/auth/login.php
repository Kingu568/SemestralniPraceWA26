<?php require_once __DIR__ . '/../layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow flex items-center justify-center">
    <div class="w-full max-w-md bg-white/85 backdrop-blur-sm border border-[#FFE1E6] rounded-3xl shadow-lg p-8">

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-semibold text-[#1F2937]">
                Přihlášení
            </h2>

            <p class="text-[#6B7280] mt-2 text-sm">
                Vítejte zpět ve FFXIV Market Helperu.
            </p>
        </div>

        <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="post" class="space-y-6">

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    E-mail
                </label>

                <input type="email"
                       id="email"
                       name="email"
                       required
                       autofocus
                       class="w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-[#FF7F96]">
                    Heslo
                </label>

                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="password-toggle-field w-full rounded-2xl bg-[#FFF5F7] border border-[#FFE1E6] px-4 py-3 pr-24 text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#FFB6C1]">

                    <button type="button"
                            class="password-toggle-button absolute right-4 top-1/2 -translate-y-1/2 text-sm text-[#FF7F96] hover:text-[#FF8FA3] font-medium">
                        Zobrazit
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-[#FFB6C1] hover:bg-[#FF8FA3] text-white px-5 py-3 rounded-full shadow-sm font-semibold transition">
                Přihlásit se
            </button>

            <p class="text-sm text-[#6B7280] text-center">
                Nemáte ještě účet?
                <a href="<?= BASE_URL ?>/index.php?url=auth/register"
                   class="text-[#FF7F96] hover:underline">
                    Zaregistrujte se
                </a>.
            </p>

        </form>
    </div>
</main>

<script>
document.querySelectorAll('.password-toggle-button').forEach(button => {
    button.addEventListener('click', () => {
        const wrapper = button.closest('.relative');
        const input = wrapper.querySelector('.password-toggle-field');

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = 'Skrýt';
        } else {
            input.type = 'password';
            button.textContent = 'Zobrazit';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>