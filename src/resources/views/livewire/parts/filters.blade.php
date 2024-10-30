<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div x-show="showFilters" x-transition class="flex flex-wrap gap-3 mb-3">
    @foreach ($filters as $key => $filter)
        @if($filter->type == "string")
            <div class="relative">
                <div class="text-sm dark:text-gray-300 text-gray-600">{{ucfirst($filter->label)}}</div>
                <label 
                    class="shadow rounded-md focus-within:ring-blue-400 dark:focus-within:ring-blue-400 bg-background-white dark:bg-background-dark relative flex justify-between gap-x-2 items-center transition-all ease-in-out duration-150 ring-1 ring-inset ring-gray-300 dark:ring-gray-500 focus-within:ring-2 outline-0 pl-3 pr-3 py-2 " 
                    for="filters-{{$filter->key}}" 
                >
                    <input 
                        wire:model.live.debounce.200ms="filters.{{$key}}.input"  
                        type="text" 
                        class="max-w-48 min-w-48 bg-transparent block w-full border-0 text-gray-900 dark:text-gray-400 p-0 outline-none ring-0 sm:text-sm sm:leading-6 focus:ring-0 focus:border-0 placeholder:text-gray-400 dark:placeholder:text-gray-300 " 
                        autocomplete="off"
                        id="filters-{{$filter->key}}" 
                        name="filters-{{$filter->key}}"
                    />             
                </label>
                <button 
                    type="button" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3 pt-5"
                    x-show="!!$wire.get('filters.{{$key}}.input')"
                    @click="$wire.set('filters.{{$key}}.input', '');"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if($filter->type == "daterange")
            <div class="relative">
                <div class="text-sm dark:text-gray-300 text-gray-600">{{ucfirst($filter->label)}}</div>
                <label 
                    class="shadow rounded-md focus-within:ring-blue-400 dark:focus-within:ring-blue-400 bg-background-white dark:bg-background-dark relative flex justify-between gap-x-2 items-center transition-all ease-in-out duration-150 ring-1 ring-inset ring-gray-300 dark:ring-gray-500 focus-within:ring-2 outline-0 pl-3 pr-3 py-2 " 
                    for="filters-{{$filter->key}}" 
                >   
                    <input 
                        wire:model.change="filters.{{$key}}.input"  
                        type="text" 
                        class="max-w-48 min-w-48 bg-transparent block w-full border-0 text-gray-900 dark:text-gray-400 p-0 outline-none ring-0 sm:text-sm sm:leading-6 focus:ring-0 focus:border-0 placeholder:text-gray-400 dark:placeholder:text-gray-300 " 
                        autocomplete="off"
                        id="filters-{{$filter->key}}" 
                        name="filters-{{$filter->key}}"
                    />             
                </label>
            
                <button 
                    type="button" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3 pt-5"
                    x-show="!!$wire.get('filters.{{$key}}.input')"  
                    @click="$wire.set('filters.{{$key}}.input', '');" 
                    style="display: none;" 
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    flatpickr("#filters-{{$filter->key}}", {
                        mode: "range",
                        dateFormat: "Y-m-d"
                    });
                });
            </script>
        @endif

        @if($filter->type == "select")
        <div class="relative">
            <div class="text-sm dark:text-gray-300 text-gray-600">{{ucfirst($filter->label)}}</div>
            <div class="min-w-[5rem]  rounded-md dark:text-gray-400 focus-within:ring-blue-400 dark:focus-within:ring-blue-400 shadow bg-background-white dark:bg-background-dark relative flex justify-between gap-x-2 items-center transition-all ease-in-out duration-150 ring-1 ring-inset ring-gray-300 dark:ring-gray-500 focus-within:ring-2 outline-0 h-10">
                <select 
                    wire:model.change="filters.{{$key}}.input" 
                    id="filters-{{$filter->key}}" 
                    name="filters-{{$filter->key}}"
                    class="bg-transparent block w-full border-0 text-gray-900 dark:text-gray-400 outline-none ring-0 sm:text-sm sm:leading-6 focus:ring-0 focus:border-0 ">
                    <option class="dark:bg-background-dark text-base" value="">{{ucfirst(__('yat::yat.all'))}}</option>
                    @foreach ($filter->options as $option)
                        <option class="dark:bg-background-dark text-md" value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        @if($filter->type == "bool")
        <label class="inline-flex items-center cursor-pointer pt-5">
            <input 
                type="checkbox" value="" 
                class="sr-only peer"
                wire:model.live="filters.{{$key}}.input" 
            >
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ms-3 text-base font-medium text-gray-900 dark:text-gray-300">{{ucfirst($filter->label)}}</span>
          </label>
        @endif
    @endforeach
</div>