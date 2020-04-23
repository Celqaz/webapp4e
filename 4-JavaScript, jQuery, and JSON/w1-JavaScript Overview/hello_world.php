<html lang="en" dir="ltr">
<!DOCTYPE html>
  <head>
    <meta charset="utf-8">
    <title>Js</title>
  </head>
  <body>
    <h1>Hi there</h1>
    <p><span id="welcome">Javascript Test</span></p>
<script type="text/javascript">
  document.write('<p>Hello World!</p>');
  console.log("log the Js");
  st = document.getElementById('welcome').innerHTML;
  console.log("ST = "+st);
  console.dir(document.getElementById('welcome'));
  // alert('This is ' + st);
  //更改innerHTML内容
  document.getElementById('welcome').innerHTML = 'Ruby on Rails';
</script>
<p>Second</p>
<p>
<a href="#" onclick="document.getElementById('stuff').innerHTML = 'Back'; return false;">Back</a>
<a href="#" onclick="document.getElementById('stuff').innerHTML = 'FORTH'; return false;">Forth</a>
</p>
<p> Hello <b><span id = "stuff">Charlie</span></b> there</p>
  </body>
</html>
