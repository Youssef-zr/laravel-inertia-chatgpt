<template>
    <div role="status" class="ml-24 mt-8 space-y-2.5 animate-pulse">
        <div class="flex items-start">
            <div class="flex items-center">
                <svg class="w-16 h-15 mr-2 text-gray-400 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                        clip-rule="evenodd"></path>
                </svg>

            </div>
            <div class="flex flex-col items-center mt-7">
                <div class="flex ml-2">
                    <div class="w-[540px] h-2.5 bg-gray-400 rounded-full mr-3 mb-4"></div>
                </div>
            </div>
        </div>
        <span class="sr-only">Loading...</span>
    </div>
</template>
