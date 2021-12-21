<?php include __DIR__ . '/../header.php'; ?>
    <div style="text-align: center;">
        <h1>Ajout d'un article</h1>
        <?php if(!empty($error)):?>
        <p style="color: red"><?=$error?></p>
        <?php endif;?>
        <form action="/phpMVC/articles/add" method="post">
            <label>Title: <input type="text" name="title" value="<?=$_POST['title'] ?? ''?>"></label><br><br>
            <label>Text: <textarea name="text"><?=$_POST['text'] ?? ''?></textarea>
            <br><br>
            <input type="submit" value="Ajouter">
        </form>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>