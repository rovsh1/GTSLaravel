<div class="<?= $class ?>">
    <?php if ($label) { ?>
    <label for="<?= $element->id ?>"><?= $label ?></label>
    <?php } ?>

    <?= $element ?>

    <?php if ($hint) { ?>
    <div class="form-element-hint"><?= $hint ?></div>
    <?php } ?>

    <?php if ($error) { ?>
    <div class="error"><?= $error ?></div>
    <?php } ?>
</div>
