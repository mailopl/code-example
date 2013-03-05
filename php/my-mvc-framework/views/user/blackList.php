<h2>Blacklist</h2>
<?php if (!empty($this->users[0])): ?>
    <?php foreach($this->users as $user): ?>
        <?php echo $user['first_name'] ?> <?php echo $user['second_name'] ?> (<?php echo $user['login'] ?>) <a href="./user/unblock/<?php echo $user['id'] ?>">odblokuj</a> <br />
    <?php endforeach; ?>
    <?php else: ?>
    Brak zablokowanych.
<?php endif; ?>
