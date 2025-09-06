<x-layouts.layout>
    <x-slot:title>Edit Profile</x-slot:title>

    <div class="max-w-screen-md w-full px-4 mx-auto 2xl:px-0 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">

        {{-- Breadcrumb --}}
        <nav class="text-base text-gray-500 mb-6 mt-2" aria-label="Breadcrumb">
            <ol class="list-reset flex flex-wrap">
                <li><a href="/user" class="text-blue-500 hover:underline">Home</a><span class="mx-2">/</span></li>
                <li><a href="/user/profile" class="text-blue-500 hover:underline">Profile</a><span class="mx-2">/</span>
                </li>
                <li class="text-gray-700 dark:text-gray-300">Edit Profile</li>
            </ol>
        </nav>

        <h2 class="text-2xl font-semibold mb-8 text-center text-gray-800 dark:text-gray-100">Edit Profile</h2>

        <form id="editForm" action="{{ route('profile.update', ['user' => auth()->user()->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Full Name</label>
                    <input type="text" name="name" id="name" required
                        value="{{ old('name', auth()->user()->name) }}"
                        class="w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-gray-700 font-medium">Username</label>
                    <input type="text" name="username" id="username" required
                        value="{{ old('username', auth()->user()->username) }}"
                        class="w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" id="email" required
                        value="{{ old('email', auth()->user()->email) }}"
                        class="w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-gray-700 font-medium">Phone Number</label>
                    <input type="text" name="phone" id="phone"
                        value="{{ old('phone', auth()->user()->phone) }}"
                        class="w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                {{-- Password --}}
                <div class="md:col-span-2">
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Leave blank to keep current password"
                        class="w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                {{-- Confirm Password --}}
                <div class="md:col-span-2">
                    <label for="password_confirmation" class="block text-gray-700 font-medium">Confirm New
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="Confirm new password"
                        class="w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label for="address" class="block text-gray-700 font-medium">Detailed Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-4 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-lg">{{ old('address', auth()->user()->address) }}</textarea>
                </div>

                {{-- Destination Province --}}
                <div>
                    <label for="province" class="block text-gray-700 font-medium">Destination Province</label>
                    <select id="province" name="province_id"
                        class="w-full mt-1 px-4 py-2 bg-gray-200 border border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Select Province --</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province['id'] }}"
                                {{ old('province_id', auth()->user()->province_id) == $province['id'] ? 'selected' : '' }}>
                                {{ $province['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Destination City --}}
                <div>
                    <label for="city" class="block text-gray-700 font-medium">Destination City / Regency</label>
                    <select id="city" name="city_id"
                        class="w-full mt-1 px-4 py-2 bg-gray-200 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-50 disabled:cursor-not-allowed">
                        <option value="">-- Select City --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city['id'] }}"
                                {{ old('city_id', auth()->user()->city_id) == $city['id'] ? 'selected' : '' }}>
                                {{ $city['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Destination District --}}
                <div>
                    <label for="district" class="block text-gray-700 font-medium">Destination District</label>
                    <select id="district" name="district_id"
                        class="w-full mt-1 px-4 py-2 bg-gray-200 border border-gray-300 rounded-md shadow-sm disabled:bg-gray-50 disabled:cursor-not-allowed">
                        <option value="">-- Select District --</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district['id'] }}"
                                {{ old('district_id', auth()->user()->district_id) == $district['id'] ? 'selected' : '' }}>
                                {{ $district['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hidden Destination ID --}}
                <input type="hidden" name="destination_id" id="destination_id"
                    value="{{ old('destination_id', auth()->user()->destination_id) }}">
            </div>

            {{-- Hidden Names --}}
            <input type="hidden" name="province_name" id="province_name"
                value="{{ old('province_name', auth()->user()->province_name) }}">
            <input type="hidden" name="city_name" id="city_name"
                value="{{ old('city_name', auth()->user()->city_name) }}">
            <input type="hidden" name="district_name" id="district_name"
                value="{{ old('district_name', auth()->user()->district_name) }}">

            <div class="mt-8 text-center">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-6 py-2 rounded-lg shadow-md transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // === Province change ===
            $('select[name="province_id"]').on('change', function() {
                let provinceId = $(this).val();
                let provinceName = $(this).find('option:selected').text();
                $('#province_name').val(provinceName);

                if (provinceId) {
                    $.ajax({
                        url: `/cities/${provinceId}`,
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            $('select[name="city_id"]').empty().append(
                                `<option value="">-- Select City --</option>`);
                            $.each(response, function(index, value) {
                                $('select[name="city_id"]').append(
                                    `<option value="${value.id}" ${value.id == "{{ old('city_id', auth()->user()->city_id) }}" ? 'selected' : ''}>${value.name}</option>`
                                );
                            });

                            // Clear city_name & district_name
                            $('#city_name').val('');
                            $('#district_name').val('');

                            // Auto load districts if city already selected
                            let selectedCity = "{{ old('city_id', auth()->user()->city_id) }}";
                            if (selectedCity) {
                                $('select[name="city_id"]').trigger('change');
                            }
                        }
                    });
                }
            });

            // === City change ===
            $('select[name="city_id"]').on('change', function() {
                let cityId = $(this).val();
                let cityName = $(this).find('option:selected').text();
                $('#city_name').val(cityName);

                if (cityId) {
                    $.ajax({
                        url: `/districts/${cityId}`,
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            $('select[name="district_id"]').empty().append(
                                `<option value="">-- Select District --</option>`);
                            $.each(response, function(index, value) {
                                $('select[name="district_id"]').append(
                                    `<option value="${value.id}" ${value.id == "{{ old('district_id', auth()->user()->district_id) }}" ? 'selected' : ''}>${value.name}</option>`
                                );
                            });

                            // Set hidden district_name
                            let selectedDistrict =
                                "{{ old('district_id', auth()->user()->district_id) }}";
                            if (selectedDistrict) {
                                $('#district_name').val($(
                                        'select[name="district_id"] option:selected')
                                .text());
                            }
                        }
                    });
                }
            });

            // === District change ===
            $('select[name="district_id"]').on('change', function() {
                let districtName = $(this).find('option:selected').text();
                $('#district_name').val(districtName);
            });

            // === Auto-load on page load ===
            let selectedProvince = "{{ old('province_id', auth()->user()->province_id) }}";
            if (selectedProvince) {
                $('select[name="province_id"]').val(selectedProvince).trigger('change');
            }
        });
    </script>
</x-layouts.layout>
