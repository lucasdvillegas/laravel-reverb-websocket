<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { MessageSquareText } from "@lucide/vue";
import { usePresenceStore } from "@/stores/presence";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { index as userIndex } from "@/routes/user";
import { User } from "@/types";
import { show as messageShow } from "@/routes/message";

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: "Users",
                href: userIndex(),
            },
        ],
    },
});

const props = defineProps<{
    users: User[];
}>();

// Instancia el store para acceder reactivamente a los IDs online
const presenceStore = usePresenceStore();
</script>

<template>
    <Head title="Users" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="rounded-xl border p-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="px-4 py-2">Estado</TableHead>
                        <TableHead class="px-4 py-2">Nombre</TableHead>
                        <TableHead class="px-4 py-2">Email</TableHead>
                        <TableHead class="px-4 py-2">Creado</TableHead>
                        <TableHead class="pr-4 text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="item in props.users" :key="item.id">
                        <TableCell class="px-4 py-2">
                            <div class="flex items-center gap-2">
                                <span
                                    class="h-2.5 w-2.5 rounded-full"
                                    :class="
                                        presenceStore.onlineIds.includes(
                                            item.id,
                                        )
                                            ? 'bg-green-500'
                                            : 'bg-red-500'
                                    "
                                ></span>
                                <span class="text-xs text-muted-foreground">
                                    {{
                                        presenceStore.onlineIds.includes(
                                            item.id,
                                        )
                                            ? "Online"
                                            : "Offline"
                                    }}
                                </span>
                            </div>
                        </TableCell>

                        <TableCell class="px-4 py-2 font-medium">{{
                            item.name
                        }}</TableCell>
                        <TableCell class="px-4 py-2">{{
                            item.email
                        }}</TableCell>
                        <TableCell class="px-4 py-2">
                            {{
                                new Date(item.created_at).toLocaleDateString(
                                    "es",
                                )
                            }}
                        </TableCell>
                        <TableCell>
                            <div class="flex justify-end gap-2 pr-2">
                                <Link
                                    :href="messageShow(item.id)"
                                    class="text-blue-500 transition hover:text-blue-700"
                                >
                                    <MessageSquareText class="h-5 w-5" />
                                </Link>
                            </div>
                        </TableCell>
                    </TableRow>

                    <TableRow v-if="users.length === 0">
                        <TableCell
                            colspan="5"
                            class="py-10 text-center text-muted-foreground"
                        >
                            No hay usuarios disponibles.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
