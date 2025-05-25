<?php

use thiagoalessio\TesseractOCR\TesseractOCR;

require 'vendor/autoload.php';

$fileRead = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $tmp_file = $_FILES['file']['tmp_name'];
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/tiff', 'image/bmp'];
        $file_type = mime_content_type($tmp_file);
        
        if (!in_array($file_type, $allowed_types)) {
            $error = "Invalid file type. Only images are allowed.";
        } else {
            // Generate safe filename
            $file_name = uniqid() . '_' . time() . '_' . preg_replace('/[^a-z0-9\._-]/i', '_', strtolower($file_name));
            $upload_path = 'uploads/' . $file_name;
            
            if (move_uploaded_file($tmp_file, $upload_path)) {
                try {
                    $tesseractPath = getenv('TESSERACT_PATH') ?: '/usr/bin/tesseract';
                    $fileRead = (new TesseractOCR($upload_path))
                        ->executable($tesseractPath)
                        ->setLanguage('eng')
                        ->run();
                } catch (Exception $e) {
                    $error = "OCR Error: " . $e->getMessage();
                }
            } else {
                $error = "File upload failed. Please try again.";
            }
        }
    } else {
        $error = "No file uploaded or upload error occurred.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Reader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row mt-5">
            <div class="col-sm-8 mx-auto">
                <div class="jumbotron">
                    <h1 class="display-4">Read Text from Images</h1>
                    <p class="lead">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php elseif (!empty($fileRead)): ?>
                            <pre><?= htmlspecialchars($fileRead) ?></pre>
                        <?php endif; ?>
                    </p>
                    <hr class="my-4">
                </div>
            </div>
        </div>
        <div class="row col-sm-8 mx-auto">
            <div class="card mt-5">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="filechoose">Choose Image File</label>
                            <input type="file" name="file" class="form-control-file" id="filechoose" accept="image/*" required>
                            <button class="btn btn-success mt-3" type="submit" name="submit">Upload & Process</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</body>
</html>