@props([
    'model',
    'label',
    'color' => 'indigo',
    'name'
])

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        <span class="text-xs text-{{ $color }}-600 font-semibold" x-text="'(' + {{ $model }} + (mode === 'heures' ? 'h' : '%') + ')'"></span>
    </label>
    <input type="range"
           x-model.number="{{ $model }}"
           :min="0"
           :max="mode === 'heures' ? totalHeures : 100"
           :step="mode === 'heures' ? 1 : 5"
           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-{{ $color }}-600">
    <div class="flex justify-between items-center mt-2">
        <input type="number"
               x-model.number="{{ $model }}"
               :min="0"
               :max="mode === 'heures' ? totalHeures : 100"
               class="w-20 text-sm rounded border-gray-300">
        <span class="text-xs text-gray-500" x-text="mode === 'heures' ? 'heures' : '%'"></span>
    </div>
    <input type="hidden" name="{{ $name }}" :value="formatValue({{ $model }})">
</div>
