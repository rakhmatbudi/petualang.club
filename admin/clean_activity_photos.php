<?php
// Check your activity_photos.php for these common issues:

// ISSUE 1: Check for any output before <?php
// Make sure your file starts EXACTLY with <?php with no spaces or characters before it

// ISSUE 2: Check for missing semicolons
// Look for lines like:
// $variable = 'value'  // Missing semicolon
// Should be:
// $variable = 'value';

// ISSUE 3: Check for unclosed brackets in CSS
// Look in your <style> section for:
.some-class {
    color: red;
    // Missing closing bracket }

// ISSUE 4: Check for PHP syntax errors in JavaScript
// Look in your <script> section for:
const maxSize = <?php echo Config::MAX_FILE_SIZE ?>;  // Missing semicolon in PHP
// Should be:
const maxSize = <?php echo Config::MAX_FILE_SIZE; ?>;

// ISSUE 5: Check for wrong quotes in JavaScript
// Look for:
alert('File is too large. Maximum size is <?php echo Config::getMaxFileSizeFormatted() ?>');
// Should be:
alert('File is too large. Maximum size is <?php echo Config::getMaxFileSizeFormatted(); ?>');

// ISSUE 6: Check your file encoding
// Make sure the file is saved as UTF-8 without BOM

// ISSUE 7: Check file permissions
// Your file should have 644 permissions

echo "Run these checks on your activity_photos.php file";
?>