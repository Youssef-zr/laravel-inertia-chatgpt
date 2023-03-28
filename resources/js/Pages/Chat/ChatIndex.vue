<script setup>
import { ref, onMounted, computed } from "vue";
import { Head } from "@inertiajs/vue3";
import ChatLayout from "@/Layouts/ChatLayout.vue";
import { useForm, Link } from "@inertiajs/vue3";
import ChatContent from "@/Components/ChatContent.vue";
import Skeleton from "@/Components/Skeleton.vue";

const promtInput = ref(null);
const chatContainer = ref(null);
const showDeleteButton = ref(false);
const settingStatus = ref(false);

const props = defineProps({
    messages: Array,
    chat: null | Object,
});

const form = useForm({
    promt: "",
    temperature: 0.7,
    frequency_penalty: 0,
    presence_penalty: 0,
    max_tokens: 256,
    top_p: 1,
    system: ""
});

const submit = () => {
    const url = props.chat ? `/chat/${props.chat?.id}` : "/chat";
    form.post(url, {
        onFinish: () => clear(),
    });
};

const scrollToBottom = () => {
    if (props.chat) {
        const el = chatContainer.value;
        el.scrollTop = el.scrollHeight;
    }
};

const clear = () => {
    form.promt = "";
    promtInput.value.focus();
    scrollToBottom();
};
onMounted(() => {
    clear();
});

const title = computed(() => props.chat?.context[0].content ?? "New Chat");
</script>
<template>
    <Head :title="title" />
    <ChatLayout>
        <template #aside>
            <ul class="p-2">
                <li v-if="chat"
                    class="px-4 py-2 my-2 flex justify-between font-semibold text-green-400 bg-slate-900 hover:bg-slate-700 rounded-lg duration-200">
                    <Link href="/chat" class="w-full">New Chat</Link>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </li>
                <template v-for="message in messages" :key="message.id">
                    <li :class="[
                        message.id === chat?.id ? 'bg-slate-700' : 'bg-slate-900',
                        'px-4 py-2 my-2 flex justify-between font-semibold text-slate-400 hover:bg-slate-700 rounded-lg duration-200',
                    ]">
                        <Link :href="`/chat/${message.id}`">{{
                            message.context[0].content
                        }}</Link>
                        <div v-if="message.id === chat?.id" class="flex items-center">
                            <div class="delete">
                                <button v-if="!showDeleteButton" @click="showDeleteButton = !showDeleteButton">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-300">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                                <span v-if="showDeleteButton" class="flex justify-between">
                                    <Link :href="route('chat.destroy', chat?.id)" method="DELETE" as="button"
                                        class="text-red-300 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    </Link>

                                    <button @click="showDeleteButton = false" class="text-slate-300 hover:text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </span>
                            </div>

                            <!-- export -->
                            <a rel="stylesheet" :href="`/chat/${message.id}/export`" style="margin-bottom:4px">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 1024 1024"
                                    id="excel">
                                    <rect width="690" height="690" x="167" y="167" fill="#fff" rx="100"></rect>
                                    <g filter="url(#filter0_diii_32_196)">
                                        <path fill="#0D981B" d="M692 748.25H332V275.75H579.5L692 388.25V748.25Z"></path>
                                        <path fill="url(#paint0_linear_32_196)"
                                            d="M692 748.25H332V275.75H579.5L692 388.25V748.25Z"></path>
                                        <path fill="url(#paint1_linear_32_196)" fill-opacity=".7"
                                            d="M692 748.25H332V275.75H579.5L692 388.25V748.25Z"></path>
                                    </g>
                                    <g filter="url(#filter1_dii_32_196)">
                                        <path fill="#fff" d="M675.125 399.5H568.25V292.625L675.125 399.5Z"></path>
                                    </g>
                                    <g filter="url(#filter2_dii_32_196)">
                                        <path fill="#fff"
                                            d="M509.063 539.64L532.598 491.782H572.243L532.002 563.175L573.334 635.749H533.284L509.063 587.002L484.842 635.749H444.893L486.124 563.175L445.984 491.782H485.539L509.063 539.64Z">
                                        </path>
                                    </g>
                                    <defs>
                                        <filter id="filter0_diii_32_196" width="400" height="526.156" x="312" y="255.556"
                                            color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="13.462"></feOffset>
                                            <feGaussianBlur stdDeviation="10"></feGaussianBlur>
                                            <feColorMatrix
                                                values="0 0 0 0 0.0509804 0 0 0 0 0.596078 0 0 0 0 0.105882 0 0 0 0.6 0">
                                            </feColorMatrix>
                                            <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_32_196"></feBlend>
                                            <feBlend in="SourceGraphic" in2="effect1_dropShadow_32_196" result="shape">
                                            </feBlend>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="-20.194"></feOffset>
                                            <feGaussianBlur stdDeviation="45"></feGaussianBlur>
                                            <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                            <feColorMatrix
                                                values="0 0 0 0 0.0509804 0 0 0 0 0.596078 0 0 0 0 0.105882 0 0 0 0.6 0">
                                            </feColorMatrix>
                                            <feBlend in2="shape" result="effect2_innerShadow_32_196"></feBlend>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="-15"></feOffset>
                                            <feGaussianBlur stdDeviation="6.5"></feGaussianBlur>
                                            <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                            <feColorMatrix values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 0.3 0">
                                            </feColorMatrix>
                                            <feBlend in2="effect2_innerShadow_32_196" result="effect3_innerShadow_32_196">
                                            </feBlend>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="10"></feOffset>
                                            <feGaussianBlur stdDeviation="7"></feGaussianBlur>
                                            <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                            <feColorMatrix values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 0.25 0">
                                            </feColorMatrix>
                                            <feBlend in2="effect3_innerShadow_32_196" result="effect4_innerShadow_32_196">
                                            </feBlend>
                                        </filter>
                                        <filter id="filter1_dii_32_196" width="225.344" height="225.344" x="509.016"
                                            y="265.7" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="32.31"></feOffset>
                                            <feGaussianBlur stdDeviation="29.617"></feGaussianBlur>
                                            <feColorMatrix
                                                values="0 0 0 0 0 0 0 0 0 0.0980392 0 0 0 0 0.345098 0 0 0 0.25 0">
                                            </feColorMatrix>
                                            <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_32_196"></feBlend>
                                            <feBlend in="SourceGraphic" in2="effect1_dropShadow_32_196" result="shape">
                                            </feBlend>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="-21.54"></feOffset>
                                            <feGaussianBlur stdDeviation="13.462"></feGaussianBlur>
                                            <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                            <feColorMatrix
                                                values="0 0 0 0 0.0716667 0 0 0 0 0.498639 0 0 0 0 0.716667 0 0 0 0.29 0">
                                            </feColorMatrix>
                                            <feBlend in2="shape" result="effect2_innerShadow_32_196"></feBlend>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="-8.077"></feOffset>
                                            <feGaussianBlur stdDeviation="5.385"></feGaussianBlur>
                                            <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                            <feColorMatrix values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 0.75 0">
                                            </feColorMatrix>
                                            <feBlend in2="effect2_innerShadow_32_196" result="effect3_innerShadow_32_196">
                                            </feBlend>
                                        </filter>
                                        <filter id="filter2_dii_32_196" width="246.91" height="262.435" x="385.659"
                                            y="464.858" color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
                                            <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="32.31"></feOffset>
                                            <feGaussianBlur stdDeviation="29.617"></feGaussianBlur>
                                            <feColorMatrix
                                                values="0 0 0 0 0 0 0 0 0 0.0980392 0 0 0 0 0.345098 0 0 0 0.25 0">
                                            </feColorMatrix>
                                            <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_32_196"></feBlend>
                                            <feBlend in="SourceGraphic" in2="effect1_dropShadow_32_196" result="shape">
                                            </feBlend>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="-21.54"></feOffset>
                                            <feGaussianBlur stdDeviation="13.462"></feGaussianBlur>
                                            <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                            <feColorMatrix
                                                values="0 0 0 0 0.0716667 0 0 0 0 0.498639 0 0 0 0 0.716667 0 0 0 0.29 0">
                                            </feColorMatrix>
                                            <feBlend in2="shape" result="effect2_innerShadow_32_196"></feBlend>
                                            <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                            <feOffset dy="-8.077"></feOffset>
                                            <feGaussianBlur stdDeviation="5.385"></feGaussianBlur>
                                            <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                            <feColorMatrix values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 0.75 0">
                                            </feColorMatrix>
                                            <feBlend in2="effect2_innerShadow_32_196" result="effect3_innerShadow_32_196">
                                            </feBlend>
                                        </filter>
                                        <linearGradient id="paint0_linear_32_196" x1="512.276" x2="512.276" y1="106.062"
                                            y2="643.25" gradientUnits="userSpaceOnUse">
                                            <stop offset=".005" stop-color="#fff" stop-opacity=".08"></stop>
                                            <stop offset=".085" stop-opacity=".08"></stop>
                                            <stop offset=".337" stop-color="#fff" stop-opacity=".1"></stop>
                                            <stop offset=".661" stop-color="#fff" stop-opacity=".1"></stop>
                                            <stop offset="1" stop-opacity=".1"></stop>
                                        </linearGradient>
                                        <linearGradient id="paint1_linear_32_196" x1="512" x2="512" y1="275.75" y2="748.25"
                                            gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#0D981B"></stop>
                                            <stop offset="1" stop-color="#fff" stop-opacity="0"></stop>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </a>
                        </div>
                    </li>
                </template>
            </ul>
        </template>

        <div class="w-full flex text-white">
            <template v-if="chat">
                <div class="w-full flex h-screen bg-slate-900">
                    <div class="w-full overflow-auto pb-36" ref="chatContainer">
                        <template v-for="(content, index) in chat?.context" :key="index">
                            <ChatContent :content="content" />
                        </template>
                        <Skeleton v-show="form.processing" />
                    </div>
                </div>
            </template>
        </div>
        <template #form>
            <section class="px-6 top-0">
                <div class="w-full">
                    <div class="params">
                        <!-- settings -->
                        <a href="#" class="pb-2 settings-icon text-white flex ml-1 mb-3" @click="settingStatus = true">
                            <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 120 120"
                                class="w-6 h-6 mr-2" id="settings">
                                <defs>
                                    <linearGradient id="a" x1="60" x2="60" y1="16" y2="-104"
                                        gradientTransform="translate(0 104)" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#8e8e93"></stop>
                                        <stop offset="1" stop-color="#e5e5ea"></stop>
                                    </linearGradient>
                                    <linearGradient id="b" x1="-419.218" x2="-419.218" y1="-738.899" y2="-740.899"
                                        gradientTransform="matrix(32.005 0 0 -32 13477.133 -23616.804)"
                                        gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#b4b4b9"></stop>
                                        <stop offset="1" stop-color="#b4b4b9"></stop>
                                    </linearGradient>
                                    <linearGradient id="c" x1="-427.251" x2="-427.251" y1="-728.117" y2="-730.117"
                                        gradientTransform="matrix(48 0 0 -47.955 20567.514 -34904.894)"
                                        gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#dcdce1"></stop>
                                        <stop offset="1" stop-color="#98989d"></stop>
                                    </linearGradient>
                                </defs>
                                <path fill="url(#a)"
                                    d="M26,0H94a25.94821,25.94821,0,0,1,26,26V94a25.94821,25.94821,0,0,1-26,26H26A25.94821,25.94821,0,0,1,0,94V26A25.94818,25.94818,0,0,1,26,0Z">
                                </path>
                                <circle cx="60" cy="60" r="52" fill="#2e2e2f"></circle>
                                <path fill="url(#b)" fill-rule="evenodd"
                                    d="M59,32h1.7l.8-4h.7l.3,4.1a7.71991,7.71991,0,0,1,1.5.2l1.2-3.9.7.1-.2,4.1a8.33085,8.33085,0,0,1,1.5.4l1.6-3.7.6.2-.7,4c.5.2,1,.3,1.5.5l2-3.5.6.3-1.1,3.9c.5.2.9.5,1.4.7l2.4-3.3.6.3-1.6,3.8c.4.3.9.6,1.3.9l2.8-3,.5.4-2,3.6c.4.3.8.7,1.2,1l3.1-2.6.5.5-2.4,3.3,1.1,1.1L84,39.2l.4.5-2.7,3,.9,1.2,3.6-1.8.4.6-3.1,2.7c.3.4.5.9.8,1.3l3.8-1.4.3.6L85,48.2l.6,1.5,3.9-.9.2.6-3.5,1.8c.2.5.3,1,.5,1.5l4-.5.2.7-3.8,1.4c.1.5.2,1,.3,1.6h4.1l.1.7-3.9,1c0,.5.1,1.1.1,1.6l4,.5v.7l-4,.5a7.56868,7.56868,0,0,1-.1,1.5l3.9.9-.1.7-4,.1-.3,1.5L91,67l-.1.6-4-.4c-.1.5-.3,1-.4,1.5l3.6,1.8-.2.6-4-.9-.6,1.5,3.4,2.3-.3.6-3.9-1.3c-.2.4-.5.9-.7,1.3l3.1,2.6-.4.6-3.6-1.8a11.32046,11.32046,0,0,1-1,1.3l2.8,2.9-.4.5-3.4-2.2a5.7309,5.7309,0,0,1-1.1,1.1l2.4,3.3-.5.5-3.2-2.6a10.59078,10.59078,0,0,1-1.2,1l2,3.5-.5.4-2.8-2.9c-.4.3-.9.6-1.3.9l1.6,3.8-.6.3-2.5-3.2a9.14221,9.14221,0,0,1-1.4.7l1.2,3.9-.6.3-2.1-3.5a8.8008,8.8008,0,0,1-1.5.5l.7,4-.6.2-1.7-3.7c-.5.1-1,.3-1.5.4l.2,4.1-.7.1-1.2-3.9c-.5.1-1,.1-1.6.2l-.2,4.1h-.7l-.8-4H59l-.7,4h-.4l-.3-4.1a9.97269,9.97269,0,0,1-1.6-.2l-1.2,3.9-.7-.1.2-4.1a8.33085,8.33085,0,0,1-1.5-.4l-1.6,3.7-.6-.2.7-4c-.5-.2-1-.3-1.5-.5l-2.1,3.5-.6-.3,1.1-3.9c-.5-.2-.9-.5-1.4-.7l-2.4,3.3-.6-.3,1.6-3.8c-.4-.3-.9-.6-1.3-.9l-2.8,3-.5-.4,2-3.6c-.4-.3-.8-.7-1.2-1l-3.1,2.6-.5-.5,2.4-3.3a11.40689,11.40689,0,0,0-1.1-1.1l-3.4,2.3-.4-.5,2.8-3a14.699,14.699,0,0,1-1-1.3l-3.6,1.8-.4-.6,3.1-2.6c-.3-.4-.5-.9-.8-1.4l-3.8,1.4-.3-.6,3.4-2.3-.6-1.5-3.9.9-.2-.6,3.6-1.9c-.2-.5-.3-1-.5-1.5l-4,.5-.2-.7,3.8-1.4c-.1-.5-.2-1-.3-1.6h-4l-.1-.7,3.9-1c0-.5-.1-1-.1-1.6l-4-.5v-.7l4-.5a8.60213,8.60213,0,0,1,.1-1.6l-3.9-.9.1-.7,4-.1.3-1.5-3.8-1.4.2-.6,4,.4c.1-.5.3-1,.4-1.5l-3.6-1.8.2-.6,3.9.9.6-1.5-3.4-2.2.3-.6,3.8,1.3c.3-.5.5-.9.8-1.4l-3.1-2.6.4-.6,3.6,1.8c.3-.4.6-.9.9-1.3l-2.8-3,.4-.5,3.4,2.2A11.984,11.984,0,0,1,40.2,40l-2.4-3.3.5-.5,3.1,2.6a10.59253,10.59253,0,0,1,1.2-1l-2-3.5.5-.4,2.8,2.9c.4-.3.9-.6,1.3-.9l-1.6-3.7.6-.3,2.5,3.2a9.14311,9.14311,0,0,1,1.4-.7L47,30.5l.6-.3,2.1,3.5a8.79992,8.79992,0,0,1,1.5-.5l-.7-4,.6-.2,1.7,3.7c.5-.1,1-.3,1.5-.4l-.2-4.1.7-.1L56,32c.5-.1,1-.1,1.6-.2l.2-4.1h.7Zm1,5.4A22.5,22.5,0,1,0,82.5,59.9,22.53391,22.53391,0,0,0,60,37.4Z">
                                </path>
                                <path fill="url(#c)" fill-rule="evenodd"
                                    d="M58.5,18h2.4L62,12h1l.4,6.1c.8.1,1.6.2,2.3.3l1.8-5.9,1,.2-.3,6.2c.8.2,1.5.3,2.2.5l2.5-5.7,1,.3-1,6.2A18.02753,18.02753,0,0,1,75,21l3.2-5.5.9.4L77.4,22c.7.3,1.4.7,2,1l3.8-5.1.9.5-2.5,5.9a21.41153,21.41153,0,0,1,1.9,1.3l4.4-4.7.8.6-3.2,5.6c.6.5,1.2,1,1.7,1.5l4.9-4.1.7.7L89,30.4c.5.5,1,1.1,1.5,1.6l5.4-3.5.6.8-4.4,4.8a20.28184,20.28184,0,0,1,1.3,1.8L99.2,33l.6.8-4.9,4.3A18.57691,18.57691,0,0,1,96,40l6.2-2.2.5.9-5.4,3.7c.3.7.6,1.3.9,2l6.4-1.5.3.9-5.8,3,.6,2.1,6.5-.7.2,1-6.1,2.3c.1.7.3,1.4.4,2.1h6.6l.1,1L101,56.2a16.94811,16.94811,0,0,1,.1,2.3l6.5.8v1l-6.5.8c0,.7-.1,1.4-.1,2.1l6.4,1.5-.1,1-6.6.1a16.9594,16.9594,0,0,1-.4,2.2l6.1,2.3-.2,1-6.5-.7-.6,2.1,5.8,2.9-.3.9-6.3-1.4q-.45,1.05-.9,2.1l5.4,3.6-.4.9-6.1-2.1a11.7816,11.7816,0,0,1-1.1,1.9l4.9,4.2-.5.8-5.8-2.8c-.4.6-.9,1.2-1.3,1.8l4.4,4.7-.6.8-5.4-3.5c-.5.6-1,1.1-1.5,1.7l3.8,5.1-.7.7-4.9-4c-.6.5-1.1,1-1.7,1.5l3.1,5.5-.8.6-4.4-4.5c-.6.4-1.2.9-1.9,1.3l2.5,5.7-.9.5-3.8-4.9c-.7.4-1.4.7-2.1,1.1l1.8,6-.9.4-3.2-5.3a18.02525,18.02525,0,0,1-2.1.8l1.1,6.1-1,.3-2.5-5.6c-.7.2-1.5.4-2.3.6l.3,6-1,.2-1.8-5.7-2.4.3-.4,6-1,.1-1.1-5.9H58.5l-1.1,5.9-1-.1-.4-6c-.8-.1-1.6-.1-2.4-.2l-1.7,5.7-1-.2.3-5.9c-.8-.2-1.6-.3-2.4-.5l-2.4,5.4-1-.3.9-5.8a20.94173,20.94173,0,0,1-2.3-.8l-2.9,5-.9-.4,1.6-5.6a15.05213,15.05213,0,0,1-2.2-1.1l-3.5,4.6-.9-.5,2.2-5.3c-.7-.4-1.4-.9-2.1-1.3l-3.9,4.2-.8-.6,2.8-5c-.7-.5-1.3-1-2-1.6l-4.4,3.6-.7-.7,3.3-4.6-1.8-1.8-4.7,3.1-.7-.8,3.8-4.2c-.5-.7-1-1.3-1.5-2l-5,2.5-.6-.8,4.2-3.7a25.29321,25.29321,0,0,1-1.3-2.2l-5.3,1.9-.4-.9,4.6-3.1a24.43217,24.43217,0,0,1-1-2.4l-5.4,1.2-.3-.9,4.9-2.5c-.3-.8-.5-1.6-.8-2.5l-5.5.6-.2-1,5.2-1.9c-.2-.8-.3-1.7-.5-2.5H11.7l-.1-1,5.4-1.4c-.1-.8-.1-1.7-.2-2.5l-5.5-.7v-1l5.5-.7c0-.9.1-1.7.1-2.6l-5.4-1.3.1-1,5.6-.1c.1-.8.3-1.7.4-2.5l-5.2-1.9.2-1,5.5.6c.2-.8.5-1.6.7-2.5l-5-2.5.4-.9,5.4,1.2c.3-.8.7-1.6,1-2.3l-4.7-3.1.4-.9,5.3,1.9a25.29082,25.29082,0,0,1,1.3-2.2l-4.3-3.6.5-.8,5.1,2.5c.5-.7,1-1.4,1.5-2l-3.9-4.2.6-.8,4.8,3.1c.6-.6,1.1-1.2,1.7-1.8l-3.4-4.6.7-.7,4.5,3.6c.6-.5,1.3-1.1,1.9-1.6l-2.9-5,.8-.6,4,4.2c.7-.5,1.4-.9,2.1-1.4l-2.3-5.4.9-.5,3.6,4.6c.7-.4,1.5-.7,2.2-1.1l-1.7-5.6.9-.4,3,5.1a20.94173,20.94173,0,0,1,2.3-.8l-1-5.9,1-.3,2.4,5.4a18.91938,18.91938,0,0,1,2.3-.5l-.3-6,1-.2,1.8,5.7,2.4-.3.4-6,1-.1ZM34.1,86.8c2.7,2.6,7.1,1.5,9-1.8L53.9,66a11.97339,11.97339,0,0,0-.2-12L42.6,35.2c-1.9-3.2-6.3-4.3-9-1.6a36.8468,36.8468,0,0,0,.5,53.2Zm23-34.9a12.42173,12.42173,0,0,0,10.6,6H89.5c3.8,0,6.9-3.2,6-6.9A37.06052,37.06052,0,0,0,48.9,24.5c-3.6,1.1-4.9,5.4-3,8.7ZM67.8,62a12.25644,12.25644,0,0,0-10.5,6L46.5,87a5.77243,5.77243,0,0,0,3.2,8.6A37.07065,37.07065,0,0,0,95.6,68.8c.9-3.6-2.2-6.9-6-6.9Zm-8.3-4a2,2,0,1,0,2,2A2.00587,2.00587,0,0,0,59.5,58Z"
                                    data-name="Combined-Shape"></path>
                            </svg>
                            settings
                        </a>

                        <!-- settings modal -->
                        <div class="modal-settings bg-indigo-500" v-if="settingStatus">
                            <div class="close-modal-settings">
                                <button type="button" @click="settingStatus = false"
                                    class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2 mb-2">
                                    X
                                </button>
                            </div>
                            <div class="container mx-auto py-6">
                                <!-- start flex row -->
                                <div class="flex flex-rows-4">
                                    <!-- start mode field -->
                                    <div class="form-input flex-1">
                                        <label for=""> Model</label>
                                        <input type="number" min="0" max="1" step="0.1" value="0" class="block w-full">
                                    </div>
                                    <!-- start mode field -->
                                    <div class="form-input flex-1">
                                        <label for=""> temperature</label>
                                        <input type="number" min="0" max="1" step="0.1" v-model="form.temperature"
                                            class="block w-full">
                                    </div>
                                    <!-- start mode field -->
                                    <div class="form-input flex-1">
                                        <label for=""> Maximum length</label>
                                        <input type="number" min="0" max="2048" step="1" v-model="form.max_tokens"
                                            class="block w-full">
                                    </div>
                                </div>
                                <!-- start flex row -->
                                <div class="flex flex-rows-4">
                                    <!-- start mode field -->
                                    <div class="form-input flex-1">
                                        <label for=""> top p</label>
                                        <input type="number" min="0" max="1" step="0.1" v-model="form.top_p"
                                            class="block w-full">
                                    </div>
                                    <!-- start mode field -->
                                    <div class="form-input flex-1">
                                        <label for=""> Frequency penalty</label>
                                        <input type="number" min="0" max="2" step="0.1" v-model="form.frequency_penalty"
                                            class="block w-full">
                                    </div>
                                    <!-- start mode field -->
                                    <div class="form-input flex-1">
                                        <label for=""> Presence penalty</label>
                                        <input type="number" min="0" max="2" step="0.1" v-model="form.presence_penalty"
                                            class="block w-full">
                                    </div>
                                </div>
                                <!-- start flex row -->
                                <div class="flex flex-rows-4">
                                    <!-- start mode field -->
                                    <div class="form-input flex-1">
                                        <label for=""> system</label>
                                        <textarea name="system" class="w-full" cols="30" rows="10" v-model="form.system"
                                            placeholder="You are helpful assistant."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex-1 flex items-center">
                        <input type="text" class="w-full bg-slate-700 text-white rounded-lg" placeholder="Ask ChatGpt AI"
                            v-model="form.promt" @keyup.enter="submit" :disabled="form.processing" ref="promtInput" />


                        <div class="absolute inset-y-0 right-0 flex items-center pl-3">
                            <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 -ml-8 text-slate-200">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                            <div class="dot-typing -ml-10" v-if="form.processing"></div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </ChatLayout>
</template>
<style>
.dot-typing {
    position: relative;
    left: -9999px;
    width: 10px;
    height: 10px;
    border-radius: 5px;
    background-color: #9880ff;
    color: #9880ff;
    box-shadow: 9984px 0 0 0 #9880ff, 9999px 0 0 0 #9880ff, 10014px 0 0 0 #9880ff;
    animation: dot-typing 1.5s infinite linear;
}

@keyframes dot-typing {
    0% {
        box-shadow: 9984px 0 0 0 #9880ff, 9999px 0 0 0 #9880ff,
            10014px 0 0 0 #9880ff;
    }

    16.667% {
        box-shadow: 9984px -10px 0 0 #9880ff, 9999px 0 0 0 #9880ff,
            10014px 0 0 0 #9880ff;
    }

    33.333% {
        box-shadow: 9984px 0 0 0 #9880ff, 9999px 0 0 0 #9880ff,
            10014px 0 0 0 #9880ff;
    }

    50% {
        box-shadow: 9984px 0 0 0 #9880ff, 9999px -10px 0 0 #9880ff,
            10014px 0 0 0 #9880ff;
    }

    66.667% {
        box-shadow: 9984px 0 0 0 #9880ff, 9999px 0 0 0 #9880ff,
            10014px 0 0 0 #9880ff;
    }

    83.333% {
        box-shadow: 9984px 0 0 0 #9880ff, 9999px 0 0 0 #9880ff,
            10014px -10px 0 0 #9880ff;
    }

    100% {
        box-shadow: 9984px 0 0 0 #9880ff, 9999px 0 0 0 #9880ff,
            10014px 0 0 0 #9880ff;
    }
}

.modal-settings {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    z-index: 100;
    background: #1E293B
}

.form-input {
    background: transparent;
    border: none;
}

.form-input label {
    color: #fff;
    text-transform: capitalize;
    margin-bottom: 5px;
    display: inline-block;
}

.modal-settings .close-modal-settings {
    position: absolute;
    top: 15px;
    right: 0;
    text-transform: capitalize;
}
body{
    height: calc(100vh + 100px);
    overflow: hidden !important;
}
</style>
