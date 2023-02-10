<div class="row mb-3 <?=$class?>">
    <?=$label?>

    <div class="col-sm-10 d-flex align-items-center">
        <?=$element?>
    </div>

    <?php if ($hint): ?>
        <div class="form-element-hint"><?=$hint?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="error"><?=implode('<br>', $errors)?></div>
    <?php endif; ?>
</div>
