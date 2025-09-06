<x-mail::message>
    # {{ $title }}

    {{ $message }}

    @if ($product_link)
        <x-mail::button :url="$product_link">
            View Product
        </x-mail::button>
    @endif

    ---

    **Best regards,**
    {{ config('app.name') }}
</x-mail::message>
