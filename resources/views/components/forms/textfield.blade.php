<div>
    <label for="{{ $name }}" class="block text-sm mb-2">{{ $label }}</label>
    <input
        id="{{ $id }}"
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $isRequired() ? 'required="required"' : '' }}
        class="bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
    />
    @if ($description)
        <p class="text-gray-400"><small>{{$description}}</small></p>
    @endif
</div>
