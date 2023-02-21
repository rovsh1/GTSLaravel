<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<?= $meta->head; ?>
<body class="antialiased">
<header>
    <div class="header-inner">
        <a href="/" class="logo">AdminPanel 3.0</a>
        <div class="search">
            <input type="search" placeholder="Поиск по разделам" id="global-search-input"/>
        </div>
        <nav class="auth">
            <button id="user-btn" class="user-btn"><?php //user_avatar(Auth::user())?></button>
        </nav>
    </div>
</header>
<div class="wrapper">
    <?php //$layout->sidebar()?>
    <section class="content"><?= $content ?></section>
</div>
</body>
</html>
