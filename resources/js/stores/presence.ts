import { defineStore } from "pinia";
import { ref } from "vue";

export const usePresenceStore = defineStore("presence", () => {
    const onlineIds = ref<number[]>([]);

    const setOnline = (users: { id: number }[]) => {
        onlineIds.value = users.map((u) => u.id);
    };

    const addOnline = (user: { id: number }) => {
        if (!onlineIds.value.includes(user.id)) {
            onlineIds.value.push(user.id);
        }
    };

    const removeOnline = (user: { id: number }) => {
        onlineIds.value = onlineIds.value.filter((id) => id !== user.id);
    };

    return {
        onlineIds,
        setOnline,
        addOnline,
        removeOnline,
    };
});
