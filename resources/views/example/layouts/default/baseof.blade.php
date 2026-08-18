<!doctype html>
<html lang="en" class="dark">
  <head>
    @include('example.layouts.partials.header')
  </head>
  @php
    $whiteBg = isset($params['white_bg']) && $params['white_bg'];
  @endphp

<body class="{{ $whiteBg ? 'bg-white dark:bg-blue-950' : 'bg-gray-50 dark:bg-black' }}">

  @yield('main')
  @include('example.layouts.partials.scripts')
</body>
</html>



