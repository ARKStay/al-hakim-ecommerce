<a {{ $attributes }}
    class="{{ $active ? 'bg-gray-100' : 'text-gray-900' }} flex items-center p-2 text-base font-medium rounded-lg hover:bg-gray-100 group"
    aria-current="{{ $active ? 'page' : false }}">
    <span class="ml-3">{{ $slot }}</span>
</a>
