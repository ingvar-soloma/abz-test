<template>
    <v-app theme="white">
        <v-app-bar theme="dark">
<!--            links user.index, contacts-->
            <v-spacer></v-spacer>
            <v-btn text to="/">Docs</v-btn>
            <v-btn text to="/home">Home</v-btn>
            <v-btn text to="/users">Users</v-btn>
            <v-spacer></v-spacer>


        </v-app-bar>
        <v-main class="px-6">
            <RouterView>

            </RouterView>
        </v-main>
    </v-app>
</template>
<script setup lang="ts">
</script>
