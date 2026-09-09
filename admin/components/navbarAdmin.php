<?php #include "../data/adminmenudb.php"; ?>

<header class="Headerstick">
    <div class="leftSect">
        <img src="/static/image/poltarLogo.webp" alt="poltarIcon">
        <h2>Poltar Kenshiro</h2>
    </div>
    <div class="rightSect">
        <ul>
            <?php foreach ($Amenus as $menu): ?>
                
                <?php if ($menu['id'] != $currentPage): ?>

                    <li>
                        <a class="navlink" href="<?= $menu['link'] ?>"><?= $menu['name'] ?></a>
                    </li>
                    
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <a href="/logout" class="secondary-btn">Logout</a>
    </div>
</header>