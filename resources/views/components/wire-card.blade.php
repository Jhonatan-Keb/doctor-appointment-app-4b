@props(['title' => null, 'class' => ''])

<div {{ $attributes->merge(['class' => "rounded-2xl border p-4 bg-white dark:bg-gray-800 $class"]) }}>
  @if($title)
    <h3 class="text-lg font-semibold mb-3">{{ $title }}</h3>
  @endif
  {{ $slot }}
</div>
