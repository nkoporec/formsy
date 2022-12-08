@if ($type == 'success')
<button class="cursor-pointer w-full block uppercase mx-auto shadow bg-green-500 hover:bg-green-600 focus:shadow-outline focus:outline-none text-white text-xs py-2 rounded font-railway font-semibold">
@elseif ($type == 'warning')
<button class="cursor-pointer w-full block uppercase mx-auto shadow bg-orange-500 hover:bg-orange-600 focus:shadow-outline focus:outline-none text-white text-xs py-2 rounded font-railway font-semibold">
@elseif ($type == 'danger')
<button class="cursor-pointer w-full block uppercase mx-auto shadow bg-red-500 hover:bg-red-600 focus:shadow-outline focus:outline-none text-white text-xs py-2 rounded font-railway font-semibold">
@elseif ($type == 'alt')
<button class="cursor-pointer w-full block uppercase mx-auto shadow bg-formsydark hover:bg-formydark focus:shadow-outline focus:outline-none text-white text-xs py-2 rounded font-railway font-semibold">
@else
<button class="cursor-pointer w-full block uppercase mx-auto shadow bg-indigo-500 hover:bg-indigo-600 focus:shadow-outline focus:outline-none text-white text-xs py-2 rounded font-railway font-semibold">
@endif
    <i class="{{$icon}} text-xl mr-1"></i>
    <span class="text-xs font-semibold">{{$text}}</span>
</button>
