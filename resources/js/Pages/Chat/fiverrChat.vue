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
    system: "",
    chatId: props.chat?.id
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

const disabled = (e) => {
    e.preventDefault();
}

const title = computed(() => props.chat?.context[0].content ?? "New Chat");

let val = null;
const presets = [
    {
        id: 1,
        temperature: 0.9,
        frequency_penalty: 0,
        presence_penalty: 0,
        max_tokens: 300,
        top_p: 1,
    },
    {
        id: 2,
        temperature: 0.2,
        frequency_penalty: 0,
        presence_penalty: 1,
        max_tokens: 2563,
        top_p: 1,
        selected: false
    },
    {
        id: 3,
        temperature: 0.1,
        frequency_penalty: 1,
        presence_penalty: 1,
        max_tokens: 2045,
        top_p: 1,
        selected: false
    },
]

const loadPreset = () => {
    let $activePreset = presets.filter(function (el) {
        return el.id == val;
    }
    );

    $activePreset = $activePreset[0];
    val = $activePreset['id'];

    form.temperature = $activePreset.temperature;
    form.frequency_penalty = $activePreset.frequency_penalty;
    form.presence_penalty = $activePreset.presence_penalty;
    form.max_tokens = $activePreset.max_tokens;
    form.top_p = $activePreset.top_p;
};

const savePreset = () => {
    const url = "/preset/store";
    form.post(url, {
        onFinish: () => clear(),
    });
};
</script>
<template>
    <Head :title="title" />
    <ChatLayout>
        <template #leftSide>
            <!-- start mode field -->
            <div class="form-group">
                <label for=""> System</label>
                <textarea name="" id="" cols="30" rows="10" class="w-full h-screen"
                    placeholder="Enter system message here"></textarea>
                <button type="button" @click="submit"
                    class="focus:outline-none float-right mt-2 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Submit
                </button>
            </div>
        </template>

        <div class="w-full flex text-white">
            <template v-if="chat">
                <div class="w-full flex h-screen">
                    <div class="w-full overflow-auto pt-4 pb-36" ref="chatContainer">
                        <template v-for="(content, index) in chat?.context" :key="index">
                            <ChatContent :content="content" :messages="messages" :chat="props.chat" :id="props.chat.id" />
                        </template>
                        <Skeleton v-show="form.processing" />
                    </div>
                </div>
            </template>
        </div>
        <template #form>
            <section class="px-6 top-0">
                <div class="w-full">
                    <div class="relative flex-1 flex items-center">
                        <input type="text" class="w-full rounded-lg" placeholder="Ask ChatGpt AI" v-model="form.promt"
                            @keyup.enter="submit" :disabled="form.processing" ref="promtInput" />

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
        <template #rightSide>
            <div class="container mx-auto py-6">
                <div class="form-container">
                    <!-- start mode field -->
                    <div class="form-input flex-1">
                        <label for=""> temperature</label>
                        <input type="number" min="0" max="1" step="0.1" v-model="form.temperature" class="block w-full"
                            @keydown="disabled">
                    </div>
                    <!-- start mode field -->
                    <div class="form-input flex-1">
                        <label for=""> Maximum length</label>
                        <input type="number" min="0" max="2048" step="1" v-model="form.max_tokens" class="block w-full"
                            @keydown="disabled">
                    </div>

                    <!-- start mode field -->
                    <div class="form-input flex-1">
                        <label for=""> top p</label>
                        <input type="number" min="0" max="1" step="0.1" v-model="form.top_p" class="block w-full"
                            @keydown="disabled">
                    </div>
                    <!-- start mode field -->
                    <div class="form-input flex-1">
                        <label for=""> Frequency penalty</label>
                        <input type="number" min="0" max="2" step="0.1" v-model="form.frequency_penalty"
                            class="block w-full" @keydown="disabled">
                    </div>
                    <!-- start mode field -->
                    <div class="form-input flex-1">
                        <label for=""> Presence penalty</label>
                        <input type="number" min="0" max="2" step="0.1" v-model="form.presence_penalty" class="block w-full"
                            @keydown="disabled">
                    </div>
                </div>

                <div class="presets">
                    <div class="presets-avtion">
                        <button type="button" @click="loadPreset"
                            class="focus:outline-none float-right mt-2 text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            load
                        </button>
                        <button type="button" @click="savePreset"
                            class="focus:outline-none float-right mt-2 text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            save
                        </button>
                    </div>
                    <div class="form-input">
                        <label for="">Presets</label>
                        <select v-model="val" class="w-full">
                            <option v-for="preset in presets" :key="preset.id" :value="preset.id" :test="preset.name">( {{
                                preset.id }} )</option>
                        </select>
                    </div>
                </div>
                <div class="export" v-if="chat">
                    <!-- export -->
                    <label for="">Export excel</label>
                    <a rel="stylesheet" :href="`/chat/${chat.id}/export`" style="margin-bottom:4px">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 p-0" fill="none" viewBox="0 0 1024 1024"
                            id="excel">
                            <rect width="690" height="690" x="167" y="167" fill="#fff" rx="100"></rect>
                            <g filter="url(#filter0_diii_32_196)">
                                <path fill="#0D981B" d="M692 748.25H332V275.75H579.5L692 388.25V748.25Z"></path>
                                <path fill="url(#paint0_linear_32_196)" d="M692 748.25H332V275.75H579.5L692 388.25V748.25Z">
                                </path>
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
                                    <feColorMatrix values="0 0 0 0 0.0509804 0 0 0 0 0.596078 0 0 0 0 0.105882 0 0 0 0.6 0">
                                    </feColorMatrix>
                                    <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_32_196"></feBlend>
                                    <feBlend in="SourceGraphic" in2="effect1_dropShadow_32_196" result="shape">
                                    </feBlend>
                                    <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                        values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                    <feOffset dy="-20.194"></feOffset>
                                    <feGaussianBlur stdDeviation="45"></feGaussianBlur>
                                    <feComposite in2="hardAlpha" k2="-1" k3="1" operator="arithmetic"></feComposite>
                                    <feColorMatrix values="0 0 0 0 0.0509804 0 0 0 0 0.596078 0 0 0 0 0.105882 0 0 0 0.6 0">
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
                                <filter id="filter1_dii_32_196" width="225.344" height="225.344" x="509.016" y="265.7"
                                    color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                    <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                        values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                    <feOffset dy="32.31"></feOffset>
                                    <feGaussianBlur stdDeviation="29.617"></feGaussianBlur>
                                    <feColorMatrix values="0 0 0 0 0 0 0 0 0 0.0980392 0 0 0 0 0.345098 0 0 0 0.25 0">
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
                                <filter id="filter2_dii_32_196" width="246.91" height="262.435" x="385.659" y="464.858"
                                    color-interpolation-filters="sRGB" filterUnits="userSpaceOnUse">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                    <feColorMatrix in="SourceAlpha" result="hardAlpha"
                                        values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix>
                                    <feOffset dy="32.31"></feOffset>
                                    <feGaussianBlur stdDeviation="29.617"></feGaussianBlur>
                                    <feColorMatrix values="0 0 0 0 0 0 0 0 0 0.0980392 0 0 0 0 0.345098 0 0 0 0.25 0">
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
                                <linearGradient id="paint0_linear_32_196" x1="512.276" x2="512.276" y1="106.062" y2="643.25"
                                    gradientUnits="userSpaceOnUse">
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
            </div>
        </template>
    </ChatLayout>
</template>
<style lang="css">
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

.export {
    margin-top: 50px;
    padding: 10px;
}

.export label {
    font-weight: bold;
}

.export a svg {
    margin-top: -16px;
    margin-left: -26px;
}

.right-sidebar {
    padding: 15px;
    border-left: 1px solid #eee;
}

.left-sidebar {
    padding: 15px;
    border-right: 1px solid #eee;
}

.left-sidebar label {
    display: block;
    margin: 15px 15px 10px;
    font-weight: bold;
}

.left-sidebar textarea {
    border-radius: 10px;
    height: calc(100vh - 200px);
}

.form-input {
    background: transparent;
    border: none;
}

.form-input label {
    text-transform: capitalize;
    margin-bottom: 5px;
    display: inline-block;
}

.form-input select {
    display: block;
    width: 100%
}

.modal-settings .close-modal-settings {
    position: absolute;
    top: 15px;
    right: 0;
    text-transform: capitalize;
}

body {
    height: calc(100vh + 100px);
    overflow: hidden !important;
}
</style>
