<footer class="mt-auto border-t border-[#FFE1E6] bg-white/70 backdrop-blur-sm">
    <div class="container mx-auto px-6 py-4 text-center">
        <p class="text-sm text-[#6B7280]">
            FFXIV Market Helper © <?= date('Y') ?>
        </p>

        <p class="text-xs text-[#9CA3AF] mt-1">
            Your personal market companion.
        </p>
    </div>
</footer>

<script>

document.querySelectorAll('.close-notification').forEach(button => {

    button.addEventListener('click', function () {

        this.closest('.notification').remove();

    });

});

setTimeout(() => {

    document.querySelectorAll('.notification').forEach(notification => {

        notification.style.transition = 'opacity 0.5s';

        notification.style.opacity = '0';

        setTimeout(() => {

            notification.remove();

        }, 500);

    });

}, 5000);

</script>

</body>
</html>