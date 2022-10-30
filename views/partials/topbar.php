
<?php

$breadcrumb = $app->trim($app->parsedUri('path'), '/');

$menus = [
    'dashboard' => 'Главный',
    'palindrome' => 'Палиндромы',
    // 'comments' => 'Комментарии',
    'files' => 'Файлы'
];
?>

<div class="top-bar">
  <div class="top-bar-left">
    <ul class="menu">
      <li class="menu-text">brusko</li>

      <?php foreach ($menus as $menu => $label): ?>

      <?php if ($breadcrumb == $menu): ?>
        <li class="is-active">
      <?php else: ?>
        <li>
      <?php endif;?>
          <a href="/<?=$menu;?>">
            <?=$label;?>
          </a>
        </li>
      <?php endforeach;?>

    </ul>
  </div>
  <div class="top-bar-right">
    <ul class="menu">
      <li><a href="/migrate-down">Установить данные</a></li>
      <!-- <li class="menu-text">Settings</li> -->
    </ul>
    <!-- <ul class="menu">
      <li><input type="search" placeholder="Search"></li>
      <li><button type="button" class="button">Search</button></li>
    </ul> -->
  </div>
</div>

<!-- breadcrumbs -->
<ul class="breadcrumbs callout small">
  <li>
    <span class="show-for-sr">Current: </span>
    <?=array_key_exists($breadcrumb, $menus) ? $menus[$breadcrumb] : $breadcrumb;?>
  </li>
</ul>
