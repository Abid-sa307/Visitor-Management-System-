<div class="author-wrap">
    {{-- Left: photo with stacked background --}}
    <div class="author-photo-stack">
        <img
            src="{{ $author->photo_url ?? asset('images/default-author.jpg') }}"
            alt="{{ $author->name ?? 'Author photo' }}"
            class="author-photo"
            loading="lazy"
        >
    </div>

    {{-- Right: name, bio, LinkedIn --}}
    <div>
        <h3 class="author-title">
            {{ $author->name ?? 'Author Name' }}
        </h3>

        @if(!empty($author->designation))
            <p style="margin:0 0 8px;font-weight:600;color:#1e293b;">
                {{ $author->designation }}
            </p>
        @endif

        @if(!empty($author->bio))
            <p class="author-desc">
                {!! nl2br(e($author->bio)) !!}
            </p>
        @endif

        @if(!empty($author->linkedin_url))
            <a
                href="{{ $author->linkedin_url }}"
                target="_blank"
                rel="noopener noreferrer"
                class="li-badge"
                aria-label="View LinkedIn profile"
            >
                {{-- Simple LinkedIn SVG icon --}}
                <svg class="li-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor"
                          d="M4.98 3.5C4.98 4.88 3.88 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1 4.98 2.12 4.98 3.5zM.23 8.01h4.55V24H.23zM8.34 8.01h4.36v2.17h.06c.61-1.16 2.12-2.4 4.37-2.4 4.67 0 5.53 3.07 5.53 7.06V24h-4.55v-7.4c0-1.77-.03-4.04-2.46-4.04-2.46 0-2.84 1.92-2.84 3.9V24H8.34z"/>
                </svg>
            </a>
        @endif
    </div>
</div>
