<?php
include 'config.php';
$username = "xdatsenko";
$password = "828ZsmK314";
$loginUrl = "https://is.stuba.sk/system/login.pl";
$data = array(
    'lang' => 'sk',
    'login_hidden' => '1',
    'credential_0' => $username,
    'credential_1' => $password
);

$aisId = "117220"; 
$url_rozvrh = 'https://is.stuba.sk/auth/katalog/rozvrhy_view.pl';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
$result = $conn->query("SELECT COUNT(*) AS count FROM schedule");
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
    if ($count > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Data already exists in the database.']);
        exit;
    }
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 

$response = curl_exec($ch);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'rozvrh_student_obec' => 1, 
    'zobraz' => 1, 
    'format' => 'list', 
    'rozvrh_student' => $aisId,
    'zpet' => '../student/moje_studium.pl?_m=3110,lang=sk,studium=167954,obdobi=630', 
    'lang' => 'sk'
)));
curl_setopt($ch, CURLOPT_URL, $url_rozvrh);
curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");

$content = curl_exec($ch);

$dom = new DOMDocument();
$dom->loadHTML($content);
$xpath = new DOMXPath($dom);

$trNodes = $xpath->query('//tbody/tr[contains(@class, "uis-hl-table")]');
$timetable = array();
foreach ($trNodes as $trNode) {
    $tdNodes = $trNode->getElementsByTagName('td');

    $day = $tdNodes->item(0)->nodeValue; 
    $from = $tdNodes->item(1)->nodeValue; 
    $from = str_replace(".", ":", $from);
    $from = date("H:i", strtotime($from));
    $to = $tdNodes->item(2)->nodeValue; 
    $to = str_replace(".", ":", $to);
    $to = date("H:i", strtotime($to));
    $subject = $tdNodes->item(3)->nodeValue; 
    $type = $tdNodes->item(4)->nodeValue; 
    $place = $tdNodes->item(5)->nodeValue; 
    $teacher = $tdNodes->item(6)->nodeValue;

    $sql = "INSERT INTO schedule (den, cas_od, cas_do, nazov, typ, miestnost, ucitel) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $day, $from, $to, $subject, $type, $place, $teacher);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Error inserting data into the database: ' . $stmt->error]);
        exit;
    }
}

$stmt->close();

$conn->close();

echo json_encode(['status' => 'success']);
?>





