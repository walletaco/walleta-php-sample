<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$merchantCode = '123456';

$data = [
    'merchant_code' => $merchantCode,
    'invoice_reference' => 'IN-1001',
    'invoice_date' => '2021-01-01 09:00:00',
    'invoice_amount' => 1420000,
    'payer_first_name' => 'کاربر',
    'payer_last_name' => 'تست',
    'payer_national_code' => '1111111111',
    'payer_mobile' => '09123456789',
    'callback_url' => 'http://127.0.0.1/walleta-php-sample/verify.php',
    'description' => 'پرداخت سفارش IN-1001',
    'items' => [
        [
            'reference' => 'PK-0001',
            'name' => 'کالای تست ۱',
            'quantity' => 2,
            'unit_price' => 700000,
            'unit_discount' => 0,
            'unit_tax_amount' => 0,
            'total_amount' => 1400000,
        ],
        [
            'name' => 'هزینه ارسال',
            'quantity' => 1,
            'unit_price' => 20000,
            'unit_discount' => 0,
            'unit_tax_amount' => 0,
            'total_amount' => 20000,
        ],
    ],
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

$curl = curl_init('https://cpg.walleta.ir/payment/request.json');
curl_setopt_array($curl, $options);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);
curl_close($curl);

$response = json_decode($response, true);

if ($error) {
    echo 'CURL Error:' . $error;
} else {
    if ($httpCode === 200) {
        header('Location: https://cpg.walleta.ir/ticket/' . $response['token']);
    } else {
        echo 'Status Code: ' . $httpCode . '<br>';
        echo 'Error Type: ' . $response['type'] . '<br>';
        echo 'Error Message: ' . $response['message'] . '<br>';
    }
}
