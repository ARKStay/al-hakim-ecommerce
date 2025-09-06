<aside
    class="fixed top-3 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0"
    aria-label="Sidenav" id="drawer-navigation">
    <div class="overflow-y-auto py-5 px-3 h-full bg-white">
        <ul class="space-y-2">
            <li>
                <x-dashboard.side-link href="/dashboard" :active="request()->is('dashboard')">Dashboard</x-dashboard.side-link>
            </li>
            <li>
                <x-dashboard.side-link href="/dashboard/banners" :active="request()->is('dashboard/banners*')">
                    Banners
                </x-dashboard.side-link>
            </li>
            <li>
                <x-dashboard.side-link href="/dashboard/products" :active="request()->is('dashboard/products*')">
                    Products
                </x-dashboard.side-link>
            </li>
            <li class="relative">
                @php
                    $shippedOrdersCount = \App\Models\Order::where('order_status', 'pending')
                        ->where('payment_status', 'paid')
                        ->count();
                @endphp
                <x-dashboard.side-link href="/dashboard/shippeds" :active="request()->is('dashboard/shippeds*')">
                    Shipped Orders
                    @if ($shippedOrdersCount > 0)
                        <span
                            class="absolute top-0 right-0 -mt-2 -mr-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-green-500 rounded-full">
                            {{ $shippedOrdersCount }}
                        </span>
                    @endif
                </x-dashboard.side-link>
            </li>
            <li>
                <x-dashboard.side-link href="/dashboard/ratings" :active="request()->is('dashboard/ratings')">
                    Product Ratings
                </x-dashboard.side-link>
            </li>
            <li>
                <x-dashboard.side-link href="/dashboard/crm" :active="request()->is('dashboard/crm')">
                    Promotions
                </x-dashboard.side-link>
            </li>
            <li>
                <x-dashboard.side-link href="/dashboard/users" :active="request()->is('dashboard/users')">Users</x-dashboard.side-link>
            </li>
        </ul>
        <ul class="pt-5 mt-5 space-y-2 border-t border-gray-200">
            <li>
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit"
                        class="flex items-center pl-2 pr-32 py-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <svg aria-hidden="true"
                            class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
                        </svg>
                        <span class="ml-3">Log Out</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
