<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('register.agent') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <x-label for="name" :value="__('Name')" />
                <x-input id="name" class="block mt-1 w-full form-control" type="text" name="name" :value="old('name')" required autofocus />
            </div>
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />
                <x-input id="email" class="block mt-1 w-full form-control" type="email" name="email" :value="old('email')" required />
            </div>
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />
                <x-input id="password" class="block mt-1 w-full form-control" type="password" name="password" required autocomplete="new-password" />
            </div>
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-input id="password_confirmation" class="block mt-1 w-full form-control" type="password" name="password_confirmation" required />
            </div>
            <div class="mt-4">
                <x-label for="nik" :value="__('NIK')" />
                <x-input id="nik" class="block mt-1 w-full form-control" type="text" name="nik" :value="old('nik')" required />
            </div>
            <div class="mt-4">
                <x-label for="address" :value="__('Address')" />
                <x-input id="address" class="block mt-1 w-full form-control" type="text" name="address" :value="old('address')" required />
            </div>
            <div class="mt-4">
                <x-label for="phone" :value="__('Phone')" />
                <x-input id="phone" class="block mt-1 w-full form-control" type="text" name="phone" :value="old('phone')" required />
            </div>
            <div class="mt-4">
                <x-label for="document" :value="__('Upload Document (KTP)')" />
                <x-input id="document" class="block mt-1 w-full form-control" type="file" name="document" required />
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4 btn btn-primary">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>