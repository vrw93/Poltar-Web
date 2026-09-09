<header class="Headerstick">
    <div class="leftSect">
        <img src="/static/image/poltarLogo.webp" alt="poltarIcon">
        <h2>Poltar Kenshiro</h2>
    </div>
    <div class="rightSect">
        <ul id="headerNav">
            <?php foreach ($menus as $menu): ?>
                
                <?php if ($menu['id'] != $currentPage): ?>

                    <li>
                        <a class="navlink" href="<?= $menu['link'] ?>"><?= $menu['name'] ?></a>
                    </li>
                    
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <div>
            <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="/login?url=<?=$menus[$currentPage]['link'] ?? '/'?>" class="primary-btn" 
            style="padding: 5px 10px">
                login <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </a>
            <?php else: ?>
            <button class="userBtn" id="userBtn">
                <i class="fa-solid fa-user fa-lg"></i>
            </button>
            <?php endif; ?>
        </div>
        <button class="humburger" id="humburger">
            <i class="fa-solid fa-bars fa-2xl"></i>
        </button>
    </div>
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="userMenu" id="userMenu">
        <p style="margin:0px; gap: 10px"><i class="fa-solid fa-user"></i> <?=$_SESSION['username']?></p>
        <ul>
            <?php 
                if(isset($_SESSION['role_id'])): 
                    if($_SESSION['role_id'] === 1):
            ?>
            <li>
                <a class="navlink" href="/admin">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    Admin
                </a>
            </li>
            <?php 
                    endif;
                endif;
            ?>
            <li>
                <a class="navlink" href="/user/settings">
                    <i class="fa-solid fa-gear"></i>
                    Setting
                </a>
            </li>
            <li>
                <a href="/logout" class="navlink">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <?php endif; ?>
</header>