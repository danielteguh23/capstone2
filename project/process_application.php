<?php
// Pastikan folder uploads ada
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true); // Membuat folder uploads jika belum ada
}

// Pastikan file diupload dengan benar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['cv'])) {
    // Ambil nama file dan ekstensi file
    $fileName = $_FILES['cv']['name'];
    $fileTmpPath = $_FILES['cv']['tmp_name'];
    $fileSize = $_FILES['cv']['size'];
    $fileType = $_FILES['cv']['type'];

    // Tentukan ekstensi file yang diizinkan
    $allowedExtensions = ['pdf', 'doc', 'docx'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validasi apakah file ekstensi sesuai dengan yang diizinkan
    if (in_array($fileExtension, $allowedExtensions)) {
        // Tentukan nama file baru untuk disimpan
        $newFileName = strtolower(str_replace(' ', '_', $_POST['name'])) . '_cv.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            // File berhasil di-upload
            echo "CV berhasil di-upload!<br>";
        } else {
            echo "Gagal meng-upload CV. Coba lagi.<br>";
        }
    } else {
        echo "Ekstensi file tidak diizinkan. Pastikan file berbentuk PDF, DOC, atau DOCX.<br>";
    }
}

// Menangani data lain dari formulir
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$position = $_POST['position'];

// Kirimkan data ke halaman assessment.php untuk memulai ujian
header("Location: assessment.php?name=$name&email=$email&position=$position"); // Redirect ke halaman assessment
exit(); // Pastikan tidak ada kode lain yang dieksekusi setelah redirect
?>
