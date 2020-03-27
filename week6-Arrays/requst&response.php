<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>HTTP & PHP Arrays</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <body>
    <!-- http://localhost/week6-Arrays/requst&response.php?x=3&y=4 -->
<h1>content of $_GET array</h1>
<p>Using print_r</p>
<pre>
<?php
print_r($_GET)
?>
</pre>
<p>Using var_dump</p>
<pre>
<?php
var_dump($_GET)
?>
<?php
$stuff = array('course' => 'PHP-Intro', 'topic' => 'Arrays');
echo isset($stuff['section']);
 ?>
</pre>
  </body>
</html>
