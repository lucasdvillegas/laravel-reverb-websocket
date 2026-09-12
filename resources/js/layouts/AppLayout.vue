<script setup lang="ts">
import AppLayout from "@/layouts/app/AppSidebarLayout.vue";
import type { BreadcrumbItem } from "@/types";
import { useEchoPresence } from "@laravel/echo-vue";
import { usePresenceStore } from "@/stores/presence";

const presenceStore = usePresenceStore();

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();

// Nos conectamos al canal de presencia global "online"
const { channel } = useEchoPresence("online", [], () => {});
const presenceChannel = channel();

// Sincronizamos automáticamente con Pinia
presenceChannel.here(presenceStore.setOnline);
presenceChannel.joining(presenceStore.addOnline);
presenceChannel.leaving(presenceStore.removeOnline);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
