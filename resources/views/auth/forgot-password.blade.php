<x-layouts.layout>
    <x-slot:title>Forgot Password</x-slot:title>

    <div class="flex flex-col items-center justify-center px-6 py-10 mx-auto lg:py-16">
        <div class="w-full bg-white rounded-lg shadow sm:max-w-md xl:p-0">
            <div class="p-6 space-y-4 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Forgot your password?
                </h1>

                <p class="text-sm text-gray-600">
                    No worries! Just enter your email and we’ll send you a password reset link.
                </p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="p-3 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
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
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            autofocus class="{{ $inputClass }}" placeholder="you@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        title="The reset link will be sent to your email. Check your inbox or spam folder."
                        class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Send Reset Link
                    </button>
                </form>
                <p class="text-sm text-gray-500 text-center mt-6">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-primary-600 hover:underline font-medium">
                        Back to Login
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.layout>
