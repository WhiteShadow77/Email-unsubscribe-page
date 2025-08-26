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

    if ($responseObj->code === 200) {
        header("Location: /unsubscribe-successful.php");
    } else {
        $errorEncoded = urlencode($responseObj->error);
        header("Location: /unsubscribe-unsuccessful.php?error=" . $errorEncoded);
    }
}

if (isset($_POST['email'])) {
    unsubscribeEmail($_POST['email']);
}
?>

<html>
<meta charset="UTF-8" />
<head>
    <title>Unsubscribe from email</title>
</head>
<body>
<div>
    <form method="post" action="/email-unsubscribe.php">
        <h4>I want to unsubscribe from future emails</h4>
        <input type="text" id="email" name="email"/>
        <br/><br/>
        <button type="submit">Unsubscribe me</button>
    </form>
</div>
</body>

</html>