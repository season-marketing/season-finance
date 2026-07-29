<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./dist/css/app.css" />
    <link rel="stylesheet" href="./dist/css/extras.css" />
    <script src="./dist/js/app-2.js"></script>
    <title>Season Finance | Lead Generation Experts</title>
    <script src="https://kit.fontawesome.com/074c1172ce.js" crossorigin="anonymous"></script>
  </head>

  <body class="font-body">
    <style>
        @media screen and (min-width: 768px) {
            nav li a.isActive::after {
                content: '';
                width: 100%;
                height: 2px;
                display: block;
                position: absolute;
                margin-top: 7px;
                background: #000000;
            }
        }
    </style>
    @yield('header')
    @yield('body')
    @yield('footer')
  </body>

</html>
