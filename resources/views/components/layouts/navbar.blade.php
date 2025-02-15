<nav class="bg-gray-800" x-data="{ isOpen: false, cartOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Logo and Desktop Links -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-14 w-14" src="{{ asset('storage/banks/logo.svg') }}" alt="Your Company">
                </div>                
                <!-- Desktop Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        @auth
                            <x-layouts.nav-link href="/user" :active="request()->is('user')">Home</x-layouts.nav-link>
                            <x-layouts.nav-link href="/user/products" :active="request()->is('user/products')">Product</x-layouts.nav-link>
                            <x-layouts.nav-link href="/user/orders" :active="request()->is('user/orders')">Order Status</x-layouts.nav-link>
                            <x-layouts.nav-link href="/user/history" :active="request()->is('user/history')">Transaction History</x-layouts.nav-link>
                        @else
                            <x-layouts.nav-link href="/" :active="request()->is('/')">Home</x-layouts.nav-link>
                            <x-layouts.nav-link href="/products" :active="request()->is('products')">Product</x-layouts.nav-link>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="hidden md:flex items-center space-x-6"> <!-- Added space-x-6 for spacing -->
                @guest
                    <a href="/login"
                        class="flex items-center bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-200 ease-in-out">
                        <svg class="w-6 h-6 mr-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2" />
                        </svg>
                        Login
                    </a>
                @endguest

                @auth
                    <!-- Cart Link -->
                    <div class="relative flex items-center">
                        <a href="{{ route('user.cart') }}"
                            class="flex items-center text-gray-300 hover:text-white space-x-2 hover:bg-gray-700 px-2 py-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </a>
                        @php
                            $maincart = \App\Models\Cart::where('user_id', Auth::user()->id)
                                ->where('status', 'pending')
                                ->first();
                            $cartcount = $maincart
                                ? \App\Models\Cart_Item::where('cart_id', $maincart->id)->count()
                                : 0;
                        @endphp
                        @if ($cartcount > 0)
                            <span
                                class="absolute top-0 right-0 -mt-2 -mr-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                {{ $cartcount }}
                            </span>
                        @endif
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <div @click="isOpen = !isOpen"
                            class="flex items-center cursor-pointer text-gray-300 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <div x-show="isOpen" @click.away="isOpen = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg dark:bg-gray-700">
                            <a href="/user/profile"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">My
                                Profile</a>
                            <form action="/logout" method="post" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">Logout</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Menu for Mobile -->
            <div class="-mr-2 flex md:hidden">
                <button @click="isOpen = !isOpen"
                    class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                    <svg :class="{ 'hidden': isOpen, 'block': !isOpen }" class="block h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg :class="{ 'block': isOpen, 'hidden': !isOpen }" class="hidden h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="isOpen" class="md:hidden">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            @auth
                <x-layouts.nav-link-m href="/user" :active="request()->is('user')">Home</x-layouts.nav-link-m>
                <x-layouts.nav-link-m href="/user/products" :active="request()->is('user/products')">Product</x-layouts.nav-link-m>
                <x-layouts.nav-link-m href="/user/orders" :active="request()->is('user/orders')">Order Status</x-layouts.nav-link-m>
                <x-layouts.nav-link-m href="/user/history" :active="request()->is('user/history')">Transaction History<</x-layouts.nav-link-m>
            @else
                <x-layouts.nav-link-m href="/" :active="request()->is('/')">Home</x-layouts.nav-link-m>
                <x-layouts.nav-link-m href="/products" :active="request()->is('products')">Product</x-layouts.nav-link-m>
            @endauth
        </div>
        <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
                @guest
                    <a href="/login"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Login</a>
                @endguest
                @auth
                    <!-- Mobile Cart Link -->
                    <a href="{{ route('user.cart') }}"
                        class="flex items-center text-gray-300 hover:text-white space-x-2 hover:bg-gray-700 px-3 py-2 rounded-md">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M6 6h12M5 6l1 12h12l1-12H5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l3 3-3 3" />
                        </svg>
                    </a>

                    <a href="/user/profile"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Profile</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
