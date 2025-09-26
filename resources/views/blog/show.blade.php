<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post['title'] ?? 'Blog Post' }} | VMS Blog</title>

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

        /* Hero Section */
        .blog-hero {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 100px 0 60px;
            text-align: center;
        }

        .blog-hero h1 {
            font-weight: 700;
        }

        /* Post Content */
        .blog-post {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(78, 115, 223, 0.1);
            padding: 30px;
            margin-top: -50px;
            position: relative;
            z-index: 1;
        }

        .blog-post-img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .blog-post-meta span {
            margin-right: 15px;
            color: #6c757d;
        }

        .blog-post-content {
            margin-top: 20px;
            line-height: 1.8;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    
    {{-- ✅ Header --}}
    @include('layouts.header')

    

    {{-- ✅ Blog Content --}}
    <section class="blog-content py-5 flex-grow-1">
        <div class="container">
            <div class="blog-post">
                @if(!empty($post['imageUrl']))
                    <img src="{{ $post['imageUrl'] }}" alt="{{ $post['title'] }}" class="blog-post-img">
                @endif

                <div class="blog-post-meta mb-3">
                    @if(!empty($post['publishedAt']))
                        <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($post['publishedAt'])->format('M d, Y') }}</span>
                    @endif
                    <span><i class="bi bi-person"></i> {{ $post['author']['name'] ?? 'Admin' }}</span>
                </div>

                {{-- ✅ Post Body --}}
                <div class="blog-post-content">
                    @if(!empty($post['body']))
                        @foreach($post['body'] as $block)
                            @if($block['_type'] === 'block')
                                <p>{{ collect($block['children'])->pluck('text')->join(' ') }}</p>
                            @endif
                        @endforeach
                    @else
                        <p>No content available.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ✅ Footer --}}
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
