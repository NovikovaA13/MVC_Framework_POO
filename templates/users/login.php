<?php include __DIR__ . '/../header.php'; ?>
<div style="text-align: center;">
    <h1>Connexion</h1>
    <p style="color: red"><?=$error ?? ''?>
    </p>
    <form action="" method="post">
        <label>Email: <input type="text" name="email" value="<?=$_POST['email'] ?? ''?>"></label>
        <br><br>
        <label>Password: <input type="password" name="password" value="<?=$_POST['password'] ?? ''?>"></label>
        <br><br>
        <input type="submit" value="S'identifier">
    </form>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
