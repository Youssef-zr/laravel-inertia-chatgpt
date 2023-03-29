<script setup>
import { computed, toRef } from "vue";
import { useForm, Link } from "@inertiajs/vue3";

const props = defineProps({
    content: Object,
    chat: Object,
    id: Number,
    messages: Object
});
const user = computed(() => props.content?.role === "user");

const $self = this;
const form = useForm({
    "context": props.chat['context'],
    "id": props.id
});

const submit = () => {
    const url = `/chatUpdate/updateRole`;
    form.post(url);
};

const changeUser = (content) => {
    if (content.role == "user") {
        content.role = "assistant"
    } else if (content.role == "assistant") {
        content.role = "user"
    }

    console.log(props.messages);
    toRef(props.content, content)

    // submit();
}
</script>
<template>
    <div class="w-full flex text-white py-3">
        <span class="w-1/6 flex justify-end pl-4">
            <div v-if="content.role == 'user'">
                <span class="text-black">User :</span>
            </div>
            <div v-else>
                <span class="text-black">Assistant :</span>
            </div>
        </span>
        <section class="w-4/6 text-left pl-4">
            <p class="text-black">{{ content?.content }}</p>
        </section>
    </div>
</template>
