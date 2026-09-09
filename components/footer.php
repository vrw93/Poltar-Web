<?php #include "data/menudb.php"; ?>

<footer class="footers">
    <div class="nameCardF">
        <h2>POLISI TARUNA</h2>
        <h3>SMK Negeri 1 Giritontro</h3>
        <small>&copy; 2026 - All Rights Reserved</small>
    </div>
    <div class="navbarF">
        <h2>Page</h2>
        <?php foreach ($menus as $menu): ?>
                
                <?php if ($menu['id'] != $currentPage): ?>

                    <li>
                        <a class="navlink" href="<?= $menu['link'] ?>"><?= $menu['name'] ?></a>
                    </li>
                    
                <?php endif; ?>
            <?php endforeach; ?>
    </div>
    <div class="navbarF">
        <h2>Kontak</h2>
        <p>
            SMK Negeri 1 Giritontro <br>
            Jl. Raya Giritontro, Kab. Wonogiri
        </p>
    </div>
    <div class="navbarF">
        <h2>Ikuti Kami</h2>
        <li>
            <a class="navlink" href="https://www.instagram.com/poltar_smkn1giritontro/" 
            target="_blank">
                <i class="fa-brands fa-instagram fa-2xl"></i> @poltar_smkn1giritontro
            </a>
        </li>
    </div>
</footer>

<script src="/static/js/humburgerSwitch.js"></script>