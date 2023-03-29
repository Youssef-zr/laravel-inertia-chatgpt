<script setup></script>
<template>
    <div class="flex flex-wrap">
        <aside
            class="left-sidebar w-1/4 z-10 top-0 pb-3 h-screen transition duration-300"
        >
            <div>
                <slot name="leftSide" />
            </div>
        </aside>
        <div class="w-1/2 mb-6">
            <div>
                <main>
                    <slot />
                </main>
            </div>
            <div
                class="fixed z-20 w-[50%] bottom-0 h-28 bg-slate-900 bg-opacity-70 shadow-2xl lg:py-2.5"
            >
                <slot name="form" />
            </div>
        </div>
        <section
            class="w-1/4 z-12 top-0 pb-3 mt-0 pt-0 h-screen bg-white-200 transition duration-300 right-sidebar"
        >
            <div>
                <slot name="rightSide" />
            </div>
        </section>
    </div>
</template>
