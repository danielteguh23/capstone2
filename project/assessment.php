<?php
require 'import_questions.php';

// Ambil data dari URL
$name = $_GET['name'];
$email = $_GET['email'];
$position = $_GET['position'];

// Impor soal dari CSV untuk validasi
$questions = importQuestionsFromCSV('questions_50.csv'); // Pastikan jalur file CSV Anda benar

// Jika tidak ada soal yang valid
if (empty($questions)) {
    die("Tidak ada soal yang valid di file CSV.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Assessment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .question { 
            margin-bottom: 20px;
        }
        .question label {
            font-size: 1.1rem;
        }
        .timer {
            font-size: 2rem;
            color: red;
            font-weight: bold;
        }
        .question-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .question-container h5 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .question-container .form-check-label {
            font-size: 1rem;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
            border: none;
            padding: 10px 20px;
            width: 100%;
            margin-top: 20px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Online Assessment</h1>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h4><strong>Nama:</strong> <?php echo htmlspecialchars($name); ?></h4>
                <h4><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></h4>
                <h4><strong>Posisi yang Dilamar:</strong> <?php echo htmlspecialchars($position); ?></h4>

                <div class="text-center mb-4">
                    <h3>Waktu: <span id="timer" class="timer">45:00</span></h3>
                </div>

                <form action="submit_assessment.php" method="POST">
                    <!-- Kirimkan data nama, email, dan posisi ke submit_assessment.php -->
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <input type="hidden" name="position" value="<?php echo htmlspecialchars($position); ?>">

                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-container">
                            <h5><strong><?php echo ($index + 1) . ". " . htmlspecialchars($question['question']); ?></strong></h5>

                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="answers[<?php echo $index; ?>]" value="A" required>
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option1']); ?></label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="answers[<?php echo $index; ?>]" value="B">
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option2']); ?></label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="answers[<?php echo $index; ?>]" value="C">
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option3']); ?></label>
                            </div>

                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="answers[<?php echo $index; ?>]" value="D">
                                <label class="form-check-label"><?php echo htmlspecialchars($question['option4']); ?></label>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" class="submit-btn">Kirim Jawaban</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Countdown Timer JS -->
    <script>
        let countdownTimer = 45 * 60;  // 45 minutes in seconds
        const timerElement = document.getElementById("timer");

        // Function to update the countdown timer every second
        const updateTimer = () => {
            const minutes = Math.floor(countdownTimer / 60);
            const seconds = countdownTimer % 60;
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            // If the timer reaches zero, submit the form automatically
            if (countdownTimer <= 0) {
                clearInterval(timerInterval);
                document.querySelector("button[type='submit']").click();  // Automatically submit the form
            }

            countdownTimer--;
        };

        // Start the countdown timer
        const timerInterval = setInterval(updateTimer, 1000);
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
