<!-- Blade component -->
<div class='pr-2 pt-2' x-data="{ openModal: false }">
    @if ($type == 'danger')
    <a {{ $attributes }} @click="openModal = true" class="inline-block button block uppercase shadow bg-red-600 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer"><span class='flex'><i class="{{ $buttonIcon }} mr-2"></i> {{ $buttonText }}</span></a>
    @elseif ($type == 'warning')
    <a {{ $attributes }} @click="openModal = true" class="inline-block button block w-full uppercase shadow bg-orange-500 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer"><span class='flex'><i class="{{ $buttonIcon }} mr-2 mt-1"></i> {{ $buttonText }}</span></a>
    @elseif ($type == 'success')
    <a {{ $attributes }} @click="openModal = true" class="inline-block button block w-full uppercase shadow bg-green-500 focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer"><span class='flex'><i class="{{ $buttonIcon }} mr-2 mt-1"></i> {{ $buttonText }}</span></a>
    @else
    <a {{ $attributes }} @click="openModal = true" class="inline-block button block w-full uppercase shadow bg-darkgrey focus:shadow-outline focus:outline-none text-white text-xs py-3 px-5 rounded cursor-pointer"><span class='flex'><i class="{{ $buttonIcon }} mr-2 mt-1"></i> {{ $buttonText }}</span></a>
    @endif
    <div x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100 transform" x-transition:leave-end="opacity-0 transform" class="absolute top-0 left-0 w-full h-full flex items-center justify-center rounded bg-grey-600 z-50" style="background-color: rgba(0,0,0,.5);" x-show="openModal">
        <div class="text-left h-auto p-4 md:max-w-xl md:p-6 lg:p-8 shadow-xl rounded-lg mx-2 md:mx-0 bg-white shadow" @click.away="openModal = false">
            <a href="#" @click="openModal = false"><span class="float-right text-opacity-50"><i class="gg-close-o"></i></span></a>
            <p class="text-md  leading-tight mt-4">{{ $message }}</p>
            <div class="mt-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
