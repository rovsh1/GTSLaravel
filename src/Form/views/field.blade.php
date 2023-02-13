<div class="<?=$class?>">
    <?=$label?>

    <?=$element?>

    <?php if ($hint): ?>
        <div class="form-element-hint"><?=$hint?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="error"><?=implode('<br>', $errors)?></div>
    <?php endif; ?>
</div>
