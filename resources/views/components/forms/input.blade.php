@props(['name', 'label', 'type' => 'text', 'icon' => null, 'placeholder' => '', 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 font-semibold mb-2">
        {{ $label }}
    </label>

    <div class="relative">
        @if ($icon)
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-{{ $icon }}"></i>
            </span>
        @endif

        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => 'w-full ' . ($icon ? 'pl-10 ' : '') . 'px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all']) }}
            placeholder="{{ $placeholder }}">
    </div>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
