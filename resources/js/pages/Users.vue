<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { MessageSquareText } from "@lucide/vue";

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
</script>

<template>
    <Head title="Users" />

    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="rounded-xl border p-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="px-4 py-2">Nombre</TableHead>
                        <TableHead class="px-4 py-2">Email</TableHead>
                        <TableHead class="px-4 py-2">Creado</TableHead>
                        <TableHead class="pr-4 text-right">Acciones</TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="item in props.users" :key="item.id">
                        <TableCell class="px-4 py-2">{{ item.name }}</TableCell>
                        <TableCell class="px-4 py-2">{{
                            item.email
                        }}</TableCell>
                        <TableCell class="px-4 py-2">
                            {{
                                new Date(item.created_at).toLocaleDateString(
                                    "es",
                                )
                            }}</TableCell
                        >
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
                            colspan="4"
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
