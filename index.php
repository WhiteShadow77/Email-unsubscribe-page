<?php
error_reporting(0);
function unsubscribeEmail($email)
{
    include('vendor/rmccue/requests/library/Requests.php');
    Requests::register_autoloader();
    $headers = array(
        'X-CleverTap-Account-Id' => 'ACCOUNT_ID',
        'X-CleverTap-Passcode' => 'PASSCODE',
        'Content-Type' => 'application/json; charset=utf-8'
    );
    $data = '{"d":[{"objectId":"' . $email . '","type":"profile","emailOptIn": false}]}';

    $response = Requests::post('https://in1.api.clevertap.com/1/upload', $headers, $data);

    $responseObj = json_decode($response->body);

    if ($responseObj->code ===200) {
        header("Location: /index.php?result=success");
    } else {
        $errorEncoded = urlencode($responseObj->error);
        header("Location: /index.php?error=" . $errorEncoded);
    }
}

if (isset($_POST['email']) && $_POST['email'] != '') {
    unsubscribeEmail($_POST['email']);
}

if (isset($_POST['email']) && $_POST['email'] == '') {
    $errorPage = <<<ERROR_PAGE
    <html lang="en">
    <meta charset="UTF-8" />
    <head>
        <title>Unsubscribe unsuccessful</title>
    </head>
    <body>
    <div>
        <h4>Unsubscribe unsuccessful</h4>
        <h5>Email must be filled</h5>
    <br/>
    <a href="/index.php">To unsubscribe page</a>
    </div>
    </body>
    </html>
    ERROR_PAGE;
    echo $errorPage; exit;
}

if (isset($_GET['result']) && $_GET['result'] === 'success') {
    echo <<<SUCCESS_PAGE
        <html lang="en">
        <meta charset="UTF-8" />
        <head>
            <title>Unsubscribe successful</title>
        </head>
        <body>
        <div>
            <h4>Unsubscribe successful</h4>
            <br />
            <a href="/index.php">To unsubscribe page</a>
        </div>
        </body>
        </html>
    SUCCESS_PAGE;
    exit;
}
if (isset($_GET['error'])) {
    $errorPage = <<<ERROR_PAGE
    <html lang="en">
    <meta charset="UTF-8" />
    <head>
        <title>Unsubscribe unsuccessful</title>
    </head>
    <body>
    <div>
        <h4>Unsubscribe unsuccessful</h4>
    ERROR_PAGE;
    $errorPage .= "<h5>" . urldecode($_GET['error']) . "</h5";
    $errorPage .= <<<ERROR_PAGE
        <br/>
        <a href="/index.php">To unsubscribe page</a>
    </div>
    </body>
    </html>
    ERROR_PAGE;
    echo $errorPage;
    exit;
}
?>
<html lang="en">
<meta charset="UTF-8" />
<head>
    <title>Unsubscribe from email</title>
</head>
<body>
<div>
    <form method="post" action="/index.php">
        <h4>I want to unsubscribe from future emails</h4>
        <input type="text" id="email" name="email"/>
        <br/><br/>
        <button type="submit">Unsubscribe me</button>
    </form>
</div>
</body>
</html>