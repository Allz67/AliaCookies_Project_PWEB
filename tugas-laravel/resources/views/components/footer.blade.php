<footer class="footer">
    <div class="footer-container">

        <div class="footer-section">
            <div class="footer-logo">
                <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies Logo">
                <h3>Alia Cookies</h3>
            </div>
            <p class="footer-desc">
                Alia Cookies menghadirkan berbagai pilihan cookies handmade premium dengan cita rasa lezat, bahan berkualitas, dan tampilan menarik yang cocok untuk camilan harian, hadiah spesial, maupun hampers berbagai acara.
            </p>
        </div>

        <div class="footer-section">
            <h4>Menu Navigasi</h4>
            <ul class="footer-links">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('pengelolaan') }}">Pengelolaan</a></li>
                <li><a href="{{ route('profile') }}">Profile</a></li>
                <li><a href="{{ route('login') }}">Logout</a></li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Informasi Kontak</h4>
            <p>Email: <a href="mailto:aliacookies@gmail.com">aliacookies@gmail.com</a></p>
            <p>Instagram: <a href="https://www.instagram.com/aliacookies.jbr?igsh=YmZhN3ZsZTBrZzVo" target="_blank">@aliacookies.jbr</a></p>
            <p>WhatsApp: <a href="https://wa.me/6285648569562" target="_blank">+62 856-4856-9562</a></p>
            <p>Jember, Indonesia</p>
        </div>

        <div class="footer-section">
            <h4>Jam Operasional</h4>
            <p>Senin - Jumat : 08.00 - 20.00</p>
            <p>Sabtu - Minggu : 09.00 - 21.00</p>
            <p>Hari Besar Nasional : Menyesuaikan</p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>
            © {{ date('Y') }} <strong>Alia Cookies</strong>. All Rights Reserved.
            | Made with ♥
        </p>
    </div>
</footer>
