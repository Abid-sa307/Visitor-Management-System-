<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Company Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="{{ route('company.dashboard') }}">Company Panel</a>
    <div class="ms-auto">
      <span class="text-white me-3">{{ auth()->user()->name }}</span>
      <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-sm btn-light">Logout</button>
      </form>
    </div>
  </div>
</nav>

<main class="py-4">
  @yield('content')
</main>

</body>
</html>
