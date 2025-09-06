    <x-layouts.layout>
        <x-slot:title>Payment & Order Summary</x-slot:title>

        <section class="py-10 bg-gray-50 dark:bg-gray-900">
            <div class="max-w-6xl mx-auto px-6">
                {{-- Breadcrumb --}}
                <nav class="text-sm text-blue-500 dark:text-gray-300 mb-6">
                    <a href="/user" class="hover:underline">Home</a> /
                    <a href="{{ route('user.cart') }}" class="hover:underline">Cart</a> /
                    <span class="font-semibold text-gray-700 dark:text-white">Payment</span>
                </nav>

                {{-- Cek alamat, kalau belum lengkap kasih warning --}}
                @if (!$user->address || !$user->district_name || !$user->city_name || !$user->province_name)
                    <div class="mb-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded shadow-sm">
                        <strong>⚠️ Incomplete Address:</strong> Please complete your address, postal code, and
                        destination before making a payment.
                        <a href="{{ route('profile.edit', ['user' => $user->id]) }}"
                            class="underline text-blue-600 ml-1 hover:text-blue-800">
                            Edit Profile
                        </a>
                    </div>
                @endif

                {{-- Alamat --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Shipping Address</h2>
                        <a href="{{ route('profile.edit', ['user' => $user->id]) }}"
                            class="text-blue-600 hover:underline text-sm">Edit</a>
                    </div>
                    <div class="text-sm text-gray-800 dark:text-gray-300 space-y-1">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
                        <p><strong>Address:</strong> {{ $user->address ?? '-' }}</p>
                        <p><strong>Destination:</strong>
                            @php
                                $destinationParts = array_filter([
                                    $user->district_name,
                                    $user->city_name,
                                    $user->province_name,
                                ]);
                            @endphp
                            {{ count($destinationParts) ? implode(', ', $destinationParts) : '-' }}
                        </p>
                    </div>
                </div>

                {{-- Tabel Keranjang --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8 overflow-x-auto">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Shopping Cart</h2>
                    <table class="w-full text-sm text-left text-gray-800 dark:text-gray-300">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                            <tr>
                                <th class="p-3">#</th>
                                <th class="p-3">Product</th>
                                <th class="p-3">Price</th>
                                <th class="p-3">Qty</th>
                                <th class="p-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart_items as $index => $item)
                                <tr class="border-b dark:border-gray-600">
                                    <td class="p-3">{{ $index + 1 }}</td>
                                    <td class="p-3 flex space-x-3 items-center">
                                        @php
                                            $image = $item->variant->image ?? $item->variant->product->image;
                                        @endphp
                                        <img src="{{ asset('storage/' . $image) }}"
                                            class="w-12 h-12 object-cover rounded border" alt="Image">
                                        <div>
                                            <div class="font-medium">{{ $item->variant->product->name }}</div>
                                            <div class="text-xs text-gray-500">Color: {{ $item->variant->color }},
                                                Size:
                                                {{ $item->variant->size ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="p-3">Rp{{ number_format($item->variant->price, 0, ',', '.') }}</td>
                                    <td class="p-3">{{ $item->quantity }}</td>
                                    <td class="p-3">
                                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Shipping Options Section --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8">
                    <label for="layanan" class="block text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        Shipping Options
                    </label>

                    {{-- Loading Spinner --}}
                    <div id="shipping-loading"
                        class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300 mb-4"
                        style="display: none;">
                        <svg class="animate-spin h-5 w-5 text-blue-500 dark:text-blue-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span>Fetching available shipping services...</span>
                    </div>

                    {{-- Select --}}
                    <select id="layanan"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2"
                        required style="display: none;">
                        <option value="">-- Select Shipping Service --</option>
                    </select>

                    {{-- Info text --}}
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Please choose a shipping service to proceed with your payment.
                    </p>
                </div>

                {{-- Form Pembayaran --}}
                <form method="POST" id="payment-form" action="{{ route('user.payment') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Summary --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between"><span>Total
                                    Products:</span><strong>Rp{{ number_format($cart->total_price, 0, ',', '.') }}</strong>
                            </div>
                            <div class="flex justify-between"><span>Total Weight:</span><strong>{{ $totalWeight }}
                                    gram</strong></div>
                            <div class="flex justify-between"><span>Shipping Fee:</span><strong id="shipping-cost">Rp
                                    0</strong></div>
                            <div class="flex justify-between text-lg text-blue-600 dark:text-blue-400 font-bold">
                                <span>Total Payment:</span><span
                                    id="final-total">Rp{{ number_format($cart->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="shipping_service" id="shipping_service">
                    <input type="hidden" name="shipping_price" id="shipping_price">

                    {{-- Submit --}}
                    <button type="button" id="pay-button"
                        class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow mt-4">
                        Confirm Payment
                    </button>
                </form>
            </div>
        </section>

        {{-- Kirim ke JavaScript pakai hidden input --}}
        <input type="hidden" id="destination_id" value="{{ $district_id }}">
        <input type="hidden" id="weight" value="{{ $totalWeight }}">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            $(document).ready(function() {
                // Set CSRF token global untuk semua AJAX request
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Fungsi untuk format rupiah
                function formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount);
                }

                // Saat dropdown kurir berubah
                $('#courier').on('change', function() {
                    const courier = $(this).val();
                    const district_id = $('#destination_id').val();
                    const weight = $('#weight').val();

                    // Validasi input
                    if (!courier || !district_id || !weight) {
                        alert('Please make sure all data is filled correctly!');
                        return;
                    }

                    $('#loading-indicator').show(); // Tampilkan indikator loading

                    // Kirim request untuk mendapatkan ongkir
                    $.ajax({
                        url: "/check-ongkir",
                        method: "POST",
                        dataType: "json",
                        data: {
                            district_id: district_id,
                            courier: courier,
                            weight: weight
                        },
                        beforeSend: function() {
                            $('#results-ongkir').empty();
                            $('#service-selection').addClass('hidden');
                        },
                        success: function(response) {
                            if (response && response.length > 0) {
                                $('#service-selection').removeClass('hidden');
                                $('#service').empty().append(
                                    '<option value="">-- Select Service --</option>'
                                );

                                // Tampilkan semua opsi layanan dari response
                                $.each(response, function(index, value) {
                                    $('#service').append(`
                                <option 
                                    value="${value.cost}" 
                                    data-etd="${value.etd}" 
                                    data-service="${value.service}" 
                                    data-description="${value.description}">
                                    ${value.service} - ${value.description} (${value.etd}) - ${formatCurrency(value.cost)}
                                </option>
                            `);
                                });

                                // Scroll otomatis ke bawah agar user lihat layanan
                                $('html, body').animate({
                                    scrollTop: $('#service-selection').offset().top - 100
                                }, 500);
                            } else {
                                alert('No available shipping services.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error:", error);
                            alert("An error occurred while fetching shipping options.");
                        },
                        complete: function() {
                            $('#loading-indicator').hide(); // Sembunyikan indikator loading
                        }
                    });
                });

                // Saat user memilih layanan pengiriman
                $('#service').on('change', function() {
                    const cost = parseInt($(this).val());
                    const totalProduct = {{ $cart->total_price }};
                    const finalTotal = totalProduct + cost;

                    const selectedService = $(this).find(':selected').data('service');
                    $('#shipping_price').val(cost);
                    $('#shipping_service').val(selectedService);

                    // Update tampilan total biaya
                    if (!isNaN(cost)) {
                        $('#shipping-cost').text(formatCurrency(cost));
                        $('#final-total').text(formatCurrency(finalTotal));
                    }
                });
            });
        </script>
        <script>
            document.getElementById('pay-button').addEventListener('click', function() {
                const shippingService = document.getElementById('shipping_service').value;
                const shippingPrice = document.getElementById('shipping_price').value;

                if (!shippingService || !shippingPrice) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Shipping Service Required',
                        text: 'Please select a shipping service before proceeding.',
                    });
                    return;
                }

                // Kirim request ke backend untuk generate Snap token
                fetch("{{ route('payment.snap.token') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            shipping_service: shippingService,
                            shipping_price: shippingPrice
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Kalau dapet token, jalankan Midtrans Snap popup
                        if (data.token) {
                            snap.pay(data.token, {
                                onSuccess: function(result) {
                                    // Kalau pembayaran sukses, submit form ke server
                                    document.getElementById('payment-form').submit();
                                },
                                onPending: function(result) {
                                    alert(
                                        "Your payment is still pending. Please complete the payment."
                                    );
                                },
                                onError: function(result) {
                                    alert("An error occurred during payment.");
                                }
                            });
                        }
                    });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const layananSelect = document.getElementById('layanan');
                const loadingSpinner = document.getElementById('shipping-loading');

                // Get from hidden input
                const destination = document.getElementById('destination_id')?.value ?? null;
                const weight = document.getElementById('weight')?.value ?? null;
                const subtotal = Number({{ $cart->total_price ?? 0 }});

                // Elements
                const shippingCostEl = document.getElementById('shipping-cost');
                const finalTotalEl = document.getElementById('final-total');
                const shippingServiceInput = document.getElementById('shipping_service');
                const shippingPriceInput = document.getElementById('shipping_price');

                if (!destination || !weight) {
                    console.warn('Destination ID or weight is missing — cannot fetch shipping services.');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Shipping Data',
                        text: 'Please complete your address and weight information before proceeding.',
                    });
                    return;
                }

                // Show loader
                loadingSpinner.style.display = 'flex';
                layananSelect.style.display = 'none';

                fetch('{{ url('/check-ongkir') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            district_id: destination,
                            weight: weight
                        })
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response not ok: ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        layananSelect.innerHTML = '<option value="">-- Select Shipping Service --</option>';

                        if (!Array.isArray(data) || data.length === 0) {
                            loadingSpinner.innerHTML =
                                '<div class="text-yellow-600">No shipping services available for this destination.</div>';
                            Swal.fire({
                                icon: 'info',
                                title: 'No Shipping Options',
                                text: 'Unfortunately, there are no available shipping services for your destination.',
                            });
                            return;
                        }

                        // Group services by courier
                        const groups = data.reduce((acc, it) => {
                            const key = (it.courier || 'UNKNOWN').toUpperCase();
                            (acc[key] = acc[key] || []).push(it);
                            return acc;
                        }, {});

                        Object.keys(groups).sort().forEach(courierName => {
                            const optgroup = document.createElement('optgroup');
                            optgroup.label = courierName;

                            groups[courierName]
                                .sort((a, b) => (Number(a.cost) || 0) - (Number(b.cost) || 0))
                                .forEach(svc => {
                                    const opt = document.createElement('option');
                                    opt.value = JSON.stringify(svc);

                                    const etdText = svc.etd ? ` (${svc.etd})` : '';
                                    const desc = svc.description ? ` - ${svc.description}` : '';
                                    opt.textContent =
                                        `${svc.service}${desc}${etdText} — Rp${Number(svc.cost).toLocaleString('id-ID')}`;

                                    optgroup.appendChild(opt);
                                });

                            layananSelect.appendChild(optgroup);
                        });

                        loadingSpinner.style.display = 'none';
                        layananSelect.style.display = 'block';
                    })
                    .catch(err => {
                        console.error('Fetch check-ongkir error:', err);
                        loadingSpinner.innerHTML =
                            '<div class="text-red-600">Failed to load shipping services. Please refresh the page.</div>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load shipping options. Please try again later.',
                        });
                    });

                // When user selects a service
                layananSelect.addEventListener('change', function() {
                    if (!this.value) {
                        shippingCostEl.textContent = 'Rp 0';
                        finalTotalEl.textContent = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(subtotal);
                        shippingServiceInput.value = '';
                        shippingPriceInput.value = 0;
                        return;
                    }

                    const svc = JSON.parse(this.value);
                    const cost = Number(svc.cost) || 0;

                    // Fill hidden inputs for form submission
                    shippingPriceInput.value = cost;
                    const courierCode = (svc.courier || svc.courier_code || '').toString().toUpperCase();
                    const serviceCode = (svc.service || '').toString();
                    shippingServiceInput.value = `${courierCode}_${serviceCode}`;

                    // Update UI
                    shippingCostEl.textContent = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(cost);
                    const total = subtotal + cost;
                    finalTotalEl.textContent = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(total);
                });
            });
        </script>
    </x-layouts.layout>
