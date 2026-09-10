<script setup lang="ts">
import { Head, router, usePage } from "@inertiajs/vue3";
import { useForm } from "vee-validate";
import { ref } from "vue";
import { useInitials } from "@/composables/useInitials";

// Laravel Reverb - Echo-vue
import { useEcho } from "@laravel/echo-vue";

import * as yup from "yup";
import es from "yup-es";

yup.setLocale(es);

// shadcn components
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { Bubble, BubbleContent } from "@/components/ui/bubble";
import {
    Message,
    MessageAvatar,
    MessageContent,
} from "@/components/ui/message";
import { Textarea } from "@/components/ui/textarea";
import { ScrollArea } from "@/components/ui/scroll-area";
import { ArrowUpIcon } from "@lucide/vue";
import { Button } from "@/components/ui/button";

import type { Messages } from "@/types/message";
import { store as messageStore } from "@/routes/message";
import { index as userIndex } from "@/routes/user";

const page = usePage();
const props = defineProps<{
    conversation: { id: number; user_one_id: number; user_two_id: number };
    messages: Messages[];
    chatUser: { id: number; name: string; email: string };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Chat",
                href: userIndex(),
            },
        ],
    },
});

const messageList = ref(props.messages);
const saving = ref(false);
const userId = ref(page.props.auth.user.id);

const { getInitials } = useInitials();

const schema = yup.object({
    content: yup.string().required().label("Content"),
});

const { handleSubmit, defineField, setErrors } = useForm({
    validationSchema: schema,
    initialValues: {
        content: "",
        conversation_id: props.conversation.id,
    },
});

const [content] = defineField("content");

const onSubmit = handleSubmit((values) => {
    saving.value = true;

    router.post(messageStore(), values, {
        preserveScroll: true,
        onSuccess: () => {
            content.value = "";
        },
        onError: (errors) => {
            setErrors(errors);
        },
        onFinish: () => {
            saving.value = false;
        },
    });
});

// Escuchar el evento declarado en el routes\channels.php
useEcho(
    `chat.conversation.${props.conversation.id}`,
    ".message.sent",
    (e: any) => {
        if (!messageList.value.some((msg) => msg.id === e.message.id)) {
            messageList.value.push(e.message);
        }
    },
);
</script>

<template>
    <Head :title="`Chat con ${props.chatUser.name}`" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <ScrollArea class="h-[70vh] w-full mb-auto">
            <div class="flex flex-col gap-4">
                <Message
                    v-for="msg in messageList"
                    :key="msg.id"
                    :align="msg.user_id === userId ? 'end' : undefined"
                >
                    <MessageAvatar>
                        <Avatar>
                            <AvatarFallback>{{
                                getInitials(msg?.user?.name)
                            }}</AvatarFallback>
                        </Avatar>
                    </MessageAvatar>
                    <MessageContent>
                        <Bubble>
                            <BubbleContent>
                                {{ msg.content }}
                            </BubbleContent>
                        </Bubble>
                    </MessageContent>
                </Message>
            </div>
        </ScrollArea>
        <div class="flex gap-2">
            <Textarea
                v-model="content"
                placeholder="Type your message here."
                class="mt-auto"
            >
            </Textarea>
            <Button
                variant="outline"
                size="icon"
                aria-label="Submit"
                class="p-8"
                @click="onSubmit()"
                :disabled="!content.length"
                :loading="saving"
            >
                <ArrowUpIcon />
            </Button>
        </div>
    </div>
</template>
