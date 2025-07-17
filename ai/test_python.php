<?php


function analyze_image($image_id)
{
    $pythonInterpreter = "C:\Users\ph\AppData\Local\Programs\Python\Python311\python.exe";
    $python_script = "C:\\xampp\\\htdocs\\healthVision\\ai\\py_runner.py";

    // Validate Python script exists
    if (!file_exists($python_script)) {
        error_log("Python script not found: $python_script");
        return false;
    }
    // Log the execution (optional)
    error_log("Started image analysis for ID: $image_id");

    $command = escapeshellcmd($pythonInterpreter . " " . $python_script . " " . escapeshellarg($image_id)) . " 2>&1";
    exec($command, $output, $resultCode);
    var_dump($output);

    return true;
}

analyze_image(46);
