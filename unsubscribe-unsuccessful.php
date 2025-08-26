<html lang="en">
<meta charset="UTF-8" />
<head>
    <title>Unsubscribe unsuccessful</title>
</head>
<body>
<div>
    <h4>Unsubscribe unsuccessful</h4>
    <?php
    echo "<h5>" . urldecode($_GET['error']) . "</h5";
    ?>
    <br/>
    <a href="/email-unsubscribe.php">To unsubscribe page</a>
</div>
</body>
</html>