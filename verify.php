<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$merchantCode = '123456';

$data = [
    'merchant_code' => $merchantCode,
    'token' => $_GET['token'],
    'invoice_reference' => 'IN-1001',
    'invoice_amount' => 1420000,
];

$jsonData = json_encode($data);

$options = [
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ],
];

$curl = curl_init('https://cpg.walleta.ir/payment/verify.json');
curl_setopt_array($curl, $options);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

$response = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <title>فروشگاه آنلاین</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui">

    <link rel="stylesheet" href="assets/css/normalize.min.css">
    <link rel="stylesheet" href="assets/css/milligram.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<section class="container" id="invoice">
    <h3 class="title">
        <a href="index.html">فروشگاه من</a>
    </h3>

    <p class="description">صفحه تست درگاه پرداخت اعتباری والتا</p>

    <div>
        <?php if ($error) : ?>
            <p>CURL Error: <?= $error ?></p>
        <?php else : ?>
            <p>وضعیت: <?= $httpCode === 200 && $response['is_paid'] === true ? 'تراکنش موفق' : 'تراکنش ناموفق' ?></p>
            <p>کد وضعیت: <?= $httpCode ?></p>
            <p>پیام: <?= $httpCode !== 200 ? $response['message'] : '' ?></p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
