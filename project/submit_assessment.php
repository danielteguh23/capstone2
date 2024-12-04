<?php
// Pastikan file import_questions.php di-include untuk mendefinisikan fungsi importQuestionsFromCSV
require 'import_questions.php'; // Ubah jalur jika file berada di folder berbeda

// Mengambil data dari formulir
$name = $_POST['name'];
$email = $_POST['email'];
$position = $_POST['position'];
$answers = $_POST['answers'];

// Mengimpor soal dari CSV
$questions = importQuestionsFromCSV('questions_50.csv'); // Pastikan jalur file CSV Anda benar

// Jika tidak ada soal yang valid
if (empty($questions)) {
    die("Tidak ada soal yang valid di file CSV.");
}

// Menghitung skor berdasarkan jawaban yang benar
$score = 0;
foreach ($questions as $index => $question) {
    if (isset($answers[$index]) && $answers[$index] === $question['correctOption']) {
        $score++;
    }
}

// Menyimpan hasil
$result = [
    'name' => $name,
    'email' => $email,
    'position' => $position,
    'score' => $score,
    'total' => count($questions),
    'answers' => $answers,
    'submitted_at' => date('Y-m-d H:i:s')
];

// Menentukan nama file untuk menyimpan hasil
$filename = 'assessment/' . strtolower(str_replace(' ', '_', $name)) . '_' . strtolower(str_replace(' ', '_', $position)) . '.json';

// Pastikan folder 'assessment' ada
if (!file_exists('assessment')) {
    mkdir('assessment', 0777, true); // Membuat folder assessment jika belum ada
}

// Simpan hasil ke file JSON
if (file_put_contents($filename, json_encode($result, JSON_PRETTY_PRINT))) {
    // Menampilkan pesan sukses dengan countdown sebelum redirect
    echo "<script>
            // Alert berhasil disimpan
            alert('Jawaban berhasil disimpan!');
            
            // Pastikan JavaScript dijalankan setelah halaman sepenuhnya dimuat
            window.onload = function() {
                var countdown = 5;
                var resultElement = document.createElement('div');
                resultElement.style.textAlign = 'center';
                resultElement.style.fontSize = '20px';
                resultElement.style.fontWeight = 'bold';
                resultElement.style.color = '#28a745'; // Hijau untuk hasil
                resultElement.style.marginTop = '20px';
                resultElement.innerHTML = 'Hasil Anda: ' + " . $score . " + ' dari ' + " . count($questions) . " + ' soal yang benar';
                document.body.appendChild(resultElement);
                
                // Membuat elemen untuk menampilkan countdown
                var countdownElement = document.createElement('div');
                countdownElement.style.textAlign = 'center';
                countdownElement.style.fontSize = '30px';
                countdownElement.style.fontWeight = 'bold';
                countdownElement.style.color = '#007bff'; // Biru untuk countdown
                countdownElement.style.marginTop = '20px';
                countdownElement.innerHTML = 'Redirecting to Career page in ' + countdown + ' seconds...';
                document.body.appendChild(countdownElement);
                
                // Update countdown setiap detik
                var countdownInterval = setInterval(function() {
                    countdown--;
                    countdownElement.innerHTML = 'Redirecting to Career page in ' + countdown + ' seconds...';
                    
                    // Ketika countdown selesai, lakukan redirect
                    if (countdown <= 0) {
                        clearInterval(countdownInterval); // Hentikan interval
                        window.location.href = 'career.html'; // Redirect ke career.html setelah 5 detik
                    }
                }, 1000); // Update setiap detik (1000 ms)
            };
          </script>";
} else {
    echo "Gagal menyimpan hasil assessment.<br>";
    echo "<a href='career.html'>Kembali ke halaman utama</a>";
}
exit();
?>
