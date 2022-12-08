<div>
    <div class="flex">
        <input
            type="checkbox"
            id="{{$id}}"
            name="{{$name}}"
            {{ $isChecked() ? 'checked="checked"' : '' }}
            {{ $isRequired() ? 'required="required"' : '' }}
            class="mr-2 bg-gray-100 text-gray-700 focus:outline-none focus:shadow-outline border border-gray-300 rounded py-2 px-4 block w-full appearance-none"
        >
        <label for="{{$name}}" class="block text-sm mb-2">{{$label}}</label>
    </div>
    @if ($description)
        <p class="text-gray-400"><small>{{$description}}</small></p>
    @endif
</div>
