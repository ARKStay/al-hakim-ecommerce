<x-layouts.layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="flex flex-col items-center justify-center px-6 py-0 mx-auto mt-5 lg:py-0 mb-5">
        <div
            class="w-full bg-white rounded-lg shadow sm:max-w-md xl:p-0">

            <!-- Notification Success -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: "{{ session('success') }}",
                            showConfirmButton: false,
                            timer: 5000,
                            toast: true,
                            position: 'mid'
                        });
                    });
                </script>
            @endif

            <!-- Notification Error -->
            @if (session('loginError'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...!',
                            text: "{{ session('loginError') }}",
                            showConfirmButton: true,
                            position: 'mid'
                        });
                    });
                </script>
            @endif

            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Login to your account
                </h1>
                <form class="space-y-4 md:space-y-6" action="/login" method="post">
                    @csrf
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your
                            email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="bg-gray-50 border text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 
                            @error('email') border-red-500 @enderror"
                            placeholder="Enter your email address" required="" autofocus autocomplete="off">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="relative">
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                        <input type="password" name="password" id="password" value="{{ old('password') }}"
                            placeholder="Enter your password"
                            class="bg-gray-50 border text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pr-10"
                            required="" autocomplete="off">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <span id="togglePassword"
                            class="absolute top-10 right-2 flex items-center text-gray-600 cursor-pointer">
                            <i class="fa fa-eye-slash"></i>
                        </span>
                    </div>
                    {{-- <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" aria-describedby="remember" type="checkbox"
                                    class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-500">Remember me</label>
                            </div>
                        </div>
                        <a href="#"
                            class="text-sm font-medium text-primary-600 hover:underline">Forgot
                            password?</a>
                    </div> --}}
                    <button type="submit"
                        class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</button>
                    <p class="text-sm font-light text-gray-500">
                        Don’t have an account yet? <a href="/register"
                            class="font-medium text-primary-600 hover:underline">Sign up</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <x-layouts.show-password />
</x-layouts.layout>
