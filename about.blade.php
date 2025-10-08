<!DOCTYPE html>
<html>
<head>
     <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- stylesheet-ul pentru UI-ul Mocha -->
  @vite(['resources/js/tutorial.js','resources/js/app.js'])
</head>
<body id='body'>
  <h1>hello world</h1>

  <!-- aici doar apelezi fișierul tău JS prin Vite -->
  @vite(['resources/js/tutorial.js','resources/js/app.js','resources/js/bootstrap.js'])
  <p>hello world</p>
    {{
         var_dump($_REQUEST,$request->all())
    }}
</body>
</html>
