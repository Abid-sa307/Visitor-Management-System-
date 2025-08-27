@include('layouts.sidebar_common')


@php
    $pages = json_decode(auth()->user()->master_pages ?? '[]');
@endphp



    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-text mx-3">VMS</div>
    </a>

    <hr class="sidebar-divider my-0">

    @foreach ($menuItems as $item)
        @if(in_array($item['key'], $pages))
            <li class="nav-item">
                <a class="nav-link" href="{{ route($item['route']) }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endif
    @endforeach



    <hr class="sidebar-divider d-none d-md-block">
</ul>
