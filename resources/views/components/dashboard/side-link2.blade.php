<a {{ $attributes }}
    class="{{ $active ? 'bg-gray-100' : 'text-gray-900' }} flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 hover:bg-gray-100 group"
    aria-current="{{ $active ? 'page' : false }}">
    <span class="ml-3">{{ $slot }}</span>
</a>
