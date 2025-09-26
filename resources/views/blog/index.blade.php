<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VMS Blog & Insights</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fc;
        color: #333;
    }

    /* Blog Hero Section */
    .blog-hero {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 80px 0 50px;
        text-align: center;
    }

    .blog-hero h1 {
        font-weight: 700;
        font-size: 2rem;
    }

    /* Blog Card */
    .blog-content .blog-post {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(78, 115, 223, 0.1);
        padding: 12px;
        margin-bottom: 20px;
    }

    .blog-post-img {
        width: 100%;
        border-radius: 6px;
        margin-bottom: 10px;
        max-height: 140px; /* 👈 smaller image */
        object-fit: cover;
    }

    .blog-post-meta span {
        margin-right: 8px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    .blog-post-title a {
        color: #224abe;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
    }

    .blog-post-title a:hover {
        text-decoration: underline;
    }

    .read-more {
        display: inline-block;
        margin-top: 5px;
        font-size: 0.8rem;
        color: #4e73df;
        font-weight: 500;
    }
</style>

</head>

<body class="d-flex flex-column min-vh-100">
    {{-- Header --}}
    @include('layouts.header')

    {{-- Blog Hero Section --}}
    <section class="blog-hero">
        <div class="container">
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
