<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>VMS Blog & Insights</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary: #4e73df;
      --primary-dark: #224abe;
      --secondary: #6f42c1;
      --light: #f8f9fc;
      --dark: #5a5c69;
      --accent: #36b9cc;
      --success: #1cc88a;
      --warning: #f6c23e;
      --info: #36b9cc;
      --danger: #e74a3b;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light);
      color: #4a4a4a;
      line-height: 1.6;
    }

    /* Blog Hero Section */
    .blog-hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 120px 0 80px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .blog-hero:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,224L48,213.3C96,203,192,181,288,160C384,139,480,117,576,122.7C672,128,768,160,864,170.7C960,181,1056,171,1152,165.3C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
      background-size: cover;
      background-position: center bottom;
    }

    .blog-hero h1 {
      font-weight: 700;
      font-size: 2.5rem;
      position: relative;
      z-index: 1;
    }

    /* Blog Card */
    .blog-content .blog-post {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
      border: 1px solid rgba(78, 115, 223, 0.1);
      padding: 16px;
      margin-bottom: 24px;
      transition: all 0.3s ease;
    }

    .blog-content .blog-post:hover {
      transform: translateY(-6px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.1);
    }

    .blog-post-img {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 12px;
      max-height: 160px;
      object-fit: cover;
    }

    .blog-post-meta span {
      margin-right: 12px;
      font-size: 0.85rem;
      color: #6c757d;
    }

    .blog-post-title a {
      color: var(--primary-dark);
      text-decoration: none;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .blog-post-title a:hover {
      color: var(--primary);
    }

    .read-more {
      display: inline-block;
      margin-top: 8px;
      font-size: 0.85rem;
      color: var(--primary);
      font-weight: 500;
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">
  {{-- Header --}}
  @include('layouts.header')

  {{-- Blog Hero Section --}}
  <section class="blog-hero">
    <div class="container blog-hero-content">
      <h1 class="display-4 fw-bold mb-4">VMS Blog & Insights</h1>
      <p class="lead">Stay updated with the latest trends, best practices, and news in visitor management systems and facility security.</p>
    </div>
  </section>

  <section class="blog-content py-5">
    <div class="container">
      <div class="row">
        @forelse($posts as $post)
          <div class="col-md-4 col-sm-6">
            <div class="blog-post">
              @if(!empty($post['imageUrl']))
                <img src="{{ $post['imageUrl'] }}" alt="{{ $post['title'] }}" class="blog-post-img">
              @endif
              <div class="blog-post-meta mb-2">
                @if(!empty($post['publishedAt']))
                  <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($post['publishedAt'])->format('M d, Y') }}</span>
                @endif
                <span><i class="bi bi-person"></i> {{ $post['author']['name'] ?? 'Admin' }}</span>
              </div>
              <h2 class="blog-post-title">
                <a href="{{ url('/blog/' . ($post['slug']['current'] ?? '')) }}">{{ $post['title'] }}</a>
              </h2>
              <a href="{{ url('/blog/' . ($post['slug']['current'] ?? '')) }}" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        @empty
          <p>No blog posts found.</p>
        @endforelse
      </div>
    </div>
  </section>

  {{-- Footer --}}
  @include('layouts.footer')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
