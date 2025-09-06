<x-layouts.layout>
    <x-slot:title>Reset Password</x-slot:title>

    <div class="flex flex-col items-center justify-center px-6 py-10 mx-auto lg:py-16">
        <div class="w-full bg-white rounded-lg shadow sm:max-w-md xl:p-0">
            <div class="p-6 space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Set a New Password
                </h1>

                <form class="space-y-6" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email address</label>
                        @php
                            $inputClass =
                                'bg-gray-50 border text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5';
                            if ($errors->has('email')) {
                                $inputClass .= ' border-red-500';
                            } else {
                                $inputClass .= ' border-gray-300';
                            }
                        @endphp
                        <input type="email" name="email" id="email" value="{{ old('email', $email) }}" required
                            autofocus class="{{ $inputClass }}" placeholder="you@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">New Password</label>
                        <input type="password" name="password" id="password" required
                            placeholder="Enter new password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">
                            Confirm New Password
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            placeholder="Repeat new password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.layout>
