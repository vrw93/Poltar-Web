<div class="pageContainer" data-target="<?=$pageId?>" data-current-page="1"
data-max-page="<?=$pageCount?>">
    <button class="controlBtn left" data-mod="-1">
        <i class="fa-solid fa-caret-left fa-2xl"></i>
    </button>

    <div class="indicator"></div>

    <button class="numberBtn active" data-page-n="1">
        1
    </button>

    <?php
        if($pageCount > 5){
            $countLimit = 3;
        }else{
            $countLimit = $pageCount;
        }
        for($i = 2;$i <= $countLimit;$i++):
    ?>
        <button class="numberBtn" data-page-n="<?=$i?>">
            <?=$i?>
        </button>
    <?php endfor; ?>

    <?php if($pageCount > 5): ?>
        <input type='number' class="pageInput"
        max="<?=$pageCount?>" min="1" value="4">

        <button class="numberBtn" data-page-n="<?=$pageCount?>">
            <?=$pageCount?>
        </button>
    <?php endif; ?>

    <button class="controlBtn right" data-mod="1">
        <i class="fa-solid fa-caret-right fa-2xl"></i>
    </button>
</div>