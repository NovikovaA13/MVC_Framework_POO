<?php include __DIR__ . '../../header.php'; ?>
<?php foreach($articles as $article): ?>

    <h2><a href="articles/<?=$article->getId()?>"><?=$article->getTitle()?></a></h2>
    <p><?=substr($article->getText(), 0, 5);?>...</p>


<?php endforeach; ?>
<?php include __DIR__ . '../../footer.php'; ?>

