<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card>
                    <v-card-title class="text-h5">Users List
                        <!-- Add User Button -->
                        <v-btn
                            rounded
                            density="compact"
                            icon="mdi-plus"
                            color="success"
                            @click="dialog = true"
                        />
                    </v-card-title>
                    <v-divider></v-divider>

                    <!-- Users List (Always Visible) -->
                    <v-list>
                        <v-list-item v-for="user in users" :key="user.id">
                            <template v-slot:prepend>
                                <v-avatar size="50">
                                    <v-img :src="user.photo" alt="User Photo"></v-img>
                                </v-avatar>
                            </template>
                            <v-list-item-content>
                                <v-list-item-title>{{ user.name }}</v-list-item-title>
                                <v-list-item-subtitle>Email: {{ user.email }}</v-list-item-subtitle>
                                <v-list-item-subtitle>Phone: {{ user.phone }}</v-list-item-subtitle>
                                <v-list-item-subtitle>Position: {{ user.position }}</v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>

                        <!-- Skeleton Loader (Only for New Users) -->
                        <v-skeleton-loader
                            v-if="loading"
                            v-for="i in 6"
                            :key="'skeleton-' + i"
                            type="list-item-avatar"
                        />
                    </v-list>

                    <!-- "Show More" Button -->
                    <v-card-actions v-if="page <= totalPages">
                        <v-btn color="primary" @click="fetchUsers" :loading="loading">Show More</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <!-- User Create Form Dialog -->
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Create New User</v-card-title>
                <v-divider></v-divider>

                <v-card-text>
                    <user-create-form @user-created="fetchUsers" @close-dialog="closeDialog"/>
                </v-card-text>

                <v-card-actions>
                    <v-btn text @click="closeDialog">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import { useUserStore } from '@/stores/userStore';
import { storeToRefs } from 'pinia';
import UserCreateForm from '@/components/UserCreateForm.vue'; // Import UserCreateForm component

export default {
    components: {
        UserCreateForm,
    },
    setup() {
        const userStore = useUserStore();
        const { users, page, totalPages, loading } = storeToRefs(userStore);
        const { fetchUsers } = userStore;

        const dialog = ref(false); // Track dialog visibility

        // Fetch users on mount
        fetchUsers();

        // Close dialog
        const closeDialog = () => {
            dialog.value = false;
        };

        return {
            users,
            page,
            totalPages,
            loading,
            fetchUsers,
            dialog,
            closeDialog,
        };
    },
};
</script>

<style scoped>
.v-card {
    margin-top: 20px;
}
.v-avatar img {
    object-fit: cover;
}
</style>
