@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-fintech-blue-light focus:ring-fintech-blue-light rounded-md shadow-sm']) !!}>{{ $slot }}</textarea>