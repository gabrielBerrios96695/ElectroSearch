<!-- resources/views/components/breadcrumb.blade.php -->
<nav class="bg-white py-3 px-5 rounded-md w-full">
    <ol class="list-reset flex text-grey-dark">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->last)
                <li><a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-700">{{ $breadcrumb['name'] }}</a></li>
                <li><span class="mx-2">/</span></li>
            @else
                <li>{{ $breadcrumb['name'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
