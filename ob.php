<?php
    // Unduh dan sertakan file Obfuscator.php dari URL yang diberikan
    $url = "https://raw.githubusercontent.com/pH-7/Obfuscator-Class/master/src/Obfuscator.php";
    $obfuscatorClassContent = file_get_contents($url);

    if ($obfuscatorClassContent === FALSE) {
        die("Error: Unable to load Obfuscator class.");
    }

    eval("?>" . $obfuscatorClassContent);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['phpFile'])) {
        $filename = $_FILES['phpFile']['tmp_name'];
        $originalName = pathinfo($_FILES['phpFile']['name'], PATHINFO_FILENAME);

        try {
            $sData = file_get_contents($filename);
            if ($sData === false) {
                throw new Exception('Failed to read the uploaded file.');
            }

            $sData = str_replace(array('<?php', '<?', '?>'), '', $sData);
            $sObfuscationData = new Obfuscator($sData, 'Class/Code NAME');
            $obfuscatedCode = '<?php ' . "\r\n" . $sObfuscationData;

            $outputFilename = $originalName . '_obfuscated.php';
            file_put_contents($outputFilename, $obfuscatedCode);

            $downloadUrl = $outputFilename;

            echo json_encode(['success' => true, 'filename' => $outputFilename, 'code' => htmlspecialchars($obfuscatedCode), 'downloadUrl' => $downloadUrl]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    ?>
