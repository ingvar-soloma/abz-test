<template>
    <v-sheet>
        <v-row>
            <v-col>
                <CreateUserView/>
                <PositionsView/>
                <UsersView/>
            </v-col>
        </v-row>
    </v-sheet>
</template>
<script setup lang="ts">
import UsersView from "@/views/UsersView.vue";
import PositionsView from "@/views/PositionsView.vue";
import CreateUserView from "@/views/CreateUserView.vue";

</script>
