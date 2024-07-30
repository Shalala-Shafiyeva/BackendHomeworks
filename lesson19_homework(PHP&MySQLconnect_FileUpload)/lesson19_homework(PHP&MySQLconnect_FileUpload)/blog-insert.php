<?php
session_start();
try {
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'connection';

    $connection = new PDO("mysql:host=$server;dbname=$db; charset=utf8", $username, $password);
} catch (PDOException $e) {
    echo $e->getMessage();
}

function post($key)
{
    return $_POST[$key] ?? null;
}
$directory = 'uploads/';

$dir = file_exists($directory);
if (!$dir) {
    mkdir($directory, 0777, true);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //file upload
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = uniqid('', true) . "_" . time() . "." . $fileExtension;
        if (move_uploaded_file($fileTmp, $directory . $newFileName)) {
            // File upload successful, now insert into the database
            $query = "INSERT INTO blogs (title, content, img) VALUES (?, ?, ?)";
            $blogsQuery = $connection->prepare($query);
            $result = $blogsQuery->execute([post('title'), post('content'), $newFileName]);

            if ($result) {
                echo 'success';
            } else {
                echo 'failed';
            }
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "Wrong extension.";
    }

    if (post('title') && !empty(post('title'))) {
        $title = htmlspecialchars(post('title'));
    }
    if (post('content') && !empty(post('content'))) {
        $content = htmlspecialchars(post('content'));
    }
    if (isset($_FILES['file']) && !empty($_FILES['file'])) {
        $imgName = $newFileName;
    }

    if ($title != '' && $content != '' && $imgName != '') {
        $_SESSION['blogs'][] = [
            'title' => $title,
            'content' => $content,
            'img' => $imgName
        ];
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <form action="" method='post' enctype="multipart/form-data">
        <input type="text" name="title" placeholder="title" />
        <br>
        <input type="text" name="content" placeholder="content" />
        <br>
        <input type="file" name="file" />
        <br>
        <button type="submit">Submit</button>
    </form>
    <div class="blogs">
        <?php
        if (!empty($_SESSION['blogs'])) {
            foreach ($_SESSION['blogs'] as $blog) {
                echo "<div class='blog'>
                        <div class='img'>
                            <img src='./uploads/$blog[img]' alt=''>
                        </div>
                        <div class='title'>$blog[title]</div>
                        <div class='content'>$blog[content]</div>
                    </div>";
            }
        }
        ?>
    </div>
</body>

</html>