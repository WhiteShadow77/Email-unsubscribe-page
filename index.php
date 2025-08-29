<?php
error_reporting(0);
function unsubscribeEmail($email)
{
    include('vendor/rmccue/requests/library/Requests.php');
    Requests::register_autoloader();
    $headers = array(
        'X-CleverTap-Account-Id' => '46W-46W-9W7Z',
        'X-CleverTap-Passcode' => 'c28559db2cb24cfd95877a4ca2863f43',
        'Content-Type' => 'application/json; charset=utf-8'
    );
    $data = '{"d":[{"objectId":"' . $email . '","type":"profile","profileData": {"Email": "' . $email . '","MSG-email": false }}]}';


    $response = Requests::post('https://us1.api.clevertap.com/1/upload', $headers, $data);

    $responseArray = json_decode($response->body, true);

    if ($responseArray['status'] == 'success') {
        header("Location: /index.php?result=success");
    } else {

        $errorsForResponse = [
            'processed' => $responseArray['processed'],
            'code' => $responseArray['unprocessed'][0]['code'],
            'error' => $responseArray['unprocessed'][0]['error'],
            'record' => $responseArray['unprocessed'][0]['record'],
        ];
        $errorForResponseEncoded = urlencode(json_encode($errorsForResponse));
        header("Location: /index.php?error=" . $errorForResponseEncoded);
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
    echo $errorPage;
    exit;
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
    $errors = urldecode($_GET['error']);
    $errorsArray = json_decode($errors, true);

    $code = "<h5>Code: " . urldecode($errorsArray['code']) . "</h5>";
    $error = "<h5>Error: " . urldecode($errorsArray['error']) . "</h5>";
    $processed = "<h5>Processed: " . urldecode($errorsArray['processed']) . "</h5>";
    $record = "<h5>Record: </h5>";

    if (!is_null($errorsArray['record'])) {
        foreach ($errorsArray['record'] as $key => $value) {
            $record .= $key . ": " . $value . "<br/>";
        }
    } else {
        $record = "";
    }

    $errorPage .= $code .= $error .= $record .= <<<ERROR_PAGE
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
<meta charset="UTF-8"/>
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