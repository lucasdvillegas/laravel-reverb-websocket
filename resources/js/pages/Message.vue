<script setup lang="ts">
import { Head, router, usePage } from "@inertiajs/vue3";
import { message } from "@/routes";
import { useForm } from "vee-validate";
import { ref } from "vue";
import { useInitials } from "@/composables/useInitials";

import * as yup from "yup";
import es from "yup-es";

yup.setLocale(es);

// shadcn components
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Bubble, BubbleContent, BubbleGroup } from "@/components/ui/bubble";
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

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Message",
                href: message(),
            },
        ],
    },
});

const page = usePage();
const props = defineProps<{
    messages: Messages[];
}>();

const saving = ref(false);
const userId = ref(page.props.auth.user.id);

const { getInitials } = useInitials();

const schema = yup.object({
    content: yup.string().required().label("Content"),
});

const { handleSubmit, defineField, errors, setErrors } = useForm({
    validationSchema: schema,
    initialValues: {
        content: "",
    },
});

const [content] = defineField("content");

const onSubmit = handleSubmit((values) => {
    saving.value = true;

    router.post(message(), values, {
        preserveScroll: true,
        onSuccess: () => {
            content.value = "";
        },
        onError: (errors) => {
            setErrors(errors);
            console.log(errors);
        },
        onFinish: () => {
            saving.value = false;
        },
    });
});
</script>

<template>
    <Head title="Message" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <ScrollArea class="h-[70vh] w-full mb-auto">
            <div class="flex flex-col gap-4">
                <Message
                    v-for="msg in messages"
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
