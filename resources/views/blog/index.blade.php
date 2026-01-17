<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Blog | Visitor Management, Workplace Security &amp; Digital Check-In Insights</title>

  <meta name="description"
    content="Explore articles, guides and insights on Visitor Management Systems, digital check-in, access control, workplace security and paperless reception. Learn how offices, factories, hospitals, schools, hotels, malls, residential societies, industrial units, cold storage, temples, kabrastan and other sites use our VMS to manage visitors, staff and contractors across single and multi-location setups.">

  <meta name="keywords"
    content="visitor management blog, visitor management system articles, workplace security insights, digital check-in guides, access control and visitor tracking, office reception digitization blog, hospital visitor management tips, school and campus visitor security, residential society visitor app use cases, industrial visitor tracking best practices, cold storage and warehouse visitor logs, multi location visitor management strategies, paperless visitor register blog, VMS implementation guides, visitor experience and front desk innovation">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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

    /* ===== Hero section (like React BlogList header) ===== */
    .blog-hero {
      background: #fff;
      padding-top: calc(80px + 24px);
      /* adjust if header fixed */
      padding-bottom: 32px;
      border-bottom: 1px solid rgba(148, 163, 184, 0.2);
    }

    .blog-hero-label {
      text-transform: uppercase;
      letter-spacing: .12em;
      font-weight: 700;
      color: #6b7280;
      font-size: 13px;
      margin-bottom: 6px;
    }

    .blog-hero-title {
      font-weight: 800;
      font-size: 2.25rem;
      line-height: 1.2;
      margin: 0 0 8px;
      color: #111827;
    }

    .blog-hero-desc {
      margin: 0;
      color: #6b7280;
      max-width: min(1120px, 100%);
      font-size: 1.05rem;
      line-height: 1.6;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* ===== Blog grid + cards (ported from React styles) ===== */
    .blog-section {
      background: #fff;
      padding: 0 0 56px;
    }

    .blog-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 24px;
      margin-top: 24px;
    }

    .blog-card {
      background: #fff;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, 0.05);
      text-decoration: none !important;
      color: inherit;
      transition: transform .22s ease, box-shadow .22s ease;
      display: flex;
      flex-direction: column;
      min-height: 100%;
    }

    .blog-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    }

    .blog-card__imageWrap {
      width: 100%;
      height: 220px;
      overflow: hidden;
    }

    .blog-card__image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .blog-card__image--placeholder {
      background: #f1f3f5;
      width: 100%;
      height: 100%;
    }

    .blog-card__body {
      padding: 14px 16px 18px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .blog-card__date {
      margin: 0 0 2px 0;
      font-size: 14px;
      color: #6b7280;
    }

    .blog-card__title {
      margin: 0;
      font-size: 20px;
      line-height: 1.35;
      font-weight: 700;
      color: #111827;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .blog-card__title a {
      color: inherit;
      text-decoration: none;
    }

    .blog-card__title a:hover {
      color: #1d4ed8;
    }

    .blog-card__excerpt {
      margin: 0;
      color: #4b5563;
      font-size: 15px;
      line-height: 1.5;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      min-height: 44px;
    }

    .blog-card__read {
      margin-top: 8px;
      align-self: flex-start;
      border-radius: 999px;
      padding: 6px 14px;
      font-size: 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    @media (max-width: 480px) {
      .blog-card__imageWrap {
        height: 200px;
      }
    }
  </style>
</head>

<body class="d-flex flex-column min-vh-100">
  {{-- Header --}}
  @include('layouts.header')

  @php
    // === Helpers similar to React ===
    $pageTitle = $title ?? 'Our Blog';
    $rawDesc = $description
      ?? 'Browse the most recent blogs & news from N&T on visitor management systems—security, check-in automation and analytics.';
    // keep "analytics and" together
    $niceDesc = preg_replace('/analytics and/i', 'analytics&nbsp;and', $rawDesc);
  @endphp

  {{-- Blog Hero Section (Cement-style header) --}}
  <section class="blog-hero">
    <div class="container" style="max-width: 1140px;">
      <p class="blog-hero-label">Blogs</p>
      {{-- <h1 class="blog-hero-title">{{ $pageTitle }}</h1> --}}
      <h1 class="blog-hero-title">Visitor Management &amp; Workplace Security Blog</h1>

      <p class="blog-hero-desc" id="blog-desc" title="{!! $niceDesc !!}">
        {!! $niceDesc !!}
      </p>
    </div>
  </section>

  {{-- Blog Grid Section --}}
  <section class="blog-section">
    <div class="container" style="max-width: 1140px; padding: 0 16px;">
      @if(!isset($posts) || count($posts) === 0)
        <p class="mt-4">No blog posts found.</p>
      @else
        <div class="blog-grid" id="blog-grid">
          @foreach($posts as $index => $post)
            @php
              $imageUrl = $post['imageUrl'] ?? $post['mainImageUrl'] ?? null;

              // Prefer explicit excerpt/description if present
              $raw = trim($post['description'] ?? '') ?: trim($post['excerpt'] ?? '');

              // Fallback: build something from body (if you have portable text)
              if (!$raw && !empty($post['body'])) {
                // Very simple fallback; you can customize later
                $raw = \Illuminate\Support\Str::limit(json_encode($post['body']), 300);
              }

              $excerpt = $raw
                ? \Illuminate\Support\Str::limit($raw, 140, '…')
                : 'Read the full article →';

              $slug = $post['slug']['current'] ?? $post['slug'] ?? '';
              $url = url('/blog/' . $slug);
            @endphp

            <div class="blog-card js-blog-card" data-index="{{ $index }}">
              <div class="blog-card__imageWrap">
                @if($imageUrl)
                  <img src="{{ $imageUrl }}" alt="{{ $post['title'] ?? 'Blog image' }}" class="blog-card__image"
                    loading="lazy">
                @else
                  <div class="blog-card__image blog-card__image--placeholder"></div>
                @endif
              </div>

              <div class="blog-card__body">
                <p class="blog-card__date">
                  @if(!empty($post['publishedAt']))
                    {{ \Carbon\Carbon::parse($post['publishedAt'])->format('d-F-Y') }}
                  @endif
                </p>

                <h3 class="blog-card__title">
                  <a href="{{ $url }}">
                    {{ $post['title'] ?? 'Untitled' }}
                  </a>
                </h3>

                <p class="blog-card__excerpt">{{ $excerpt }}</p>

                {{-- Read More button --}}
                <a href="{{ $url }}" class="btn btn-outline-primary btn-sm blog-card__read">
                  Read More <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          @endforeach
        </div>

        {{-- View More button (client-side like React) --}}
        <div class="text-center">
          <button id="view-more-btn" class="btn btn-primary mt-5" style="border-radius: 999px; padding: 10px 22px;">
            View More
          </button>
        </div>
      @endif
    </div>
  </section>

  @include('components.home-contact-section')
  @stack('styles')

  {{-- Footer --}}
  @include('layouts.footer')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <script>
    // === "View More" behavior like React's visible state ===
    document.addEventListener('DOMContentLoaded', function () {
      const cards = Array.from(document.querySelectorAll('.js-blog-card'));
      const btn = document.getElementById('view-more-btn');
      if (!cards.length || !btn) return;

      let visible = 9;

      function updateVisibility() {
        cards.forEach((card, idx) => {
          card.style.display = idx < visible ? 'flex' : 'none';
        });

        if (visible >= cards.length) {
          btn.disabled = true;
          btn.textContent = 'No More Posts';
          btn.style.opacity = 0.6;
          btn.style.cursor = 'not-allowed';
        } else {
          btn.disabled = false;
          btn.textContent = 'View More';
          btn.style.opacity = 1;
          btn.style.cursor = 'pointer';
        }
      }

      btn.addEventListener('click', function () {
        if (visible < cards.length) {
          visible += 9;
          updateVisibility();
        }
      });

      // initial state
      updateVisibility();
    });
  </script>
  @stack('scripts')
</body>

</html>