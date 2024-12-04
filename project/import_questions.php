<?php
// Fungsi untuk mengimpor soal dari file CSV
function importQuestionsFromCSV($filename) {
    $questions = [];
    
    // Membuka file CSV
    if (($handle = fopen($filename, "r")) !== false) {
        $header = fgetcsv($handle); // Baca header CSV
        
        // Loop untuk membaca setiap baris data
        while (($data = fgetcsv($handle)) !== false) {
            // Pastikan jumlah kolom sesuai dengan header
            if (count($data) === count($header)) {
                $row = array_combine($header, $data); // Gabungkan header dengan data
                if ($row['type'] === 'multiple-choice') {
                    $questions[] = $row; // Tambahkan soal ke array
                }
            }
        }
        
        fclose($handle);
    }
    
    return $questions; // Mengembalikan soal
}
?>
