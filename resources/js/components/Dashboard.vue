<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" variant="dense">

            <v-list density="compact" nav class="pa-5">
                <v-list-item :to="{ path: '/dashboard/home' }" class="rounded-lg">
                    <template v-slot:prepend>
                        <v-icon size="20">mdi-home</v-icon>
                    </template>
                    <v-list-item-title>Home</v-list-item-title>
                </v-list-item>

                <v-list-group>
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" class="rounded-lg">
                            <template v-slot:prepend>
                                <v-icon size="18">mdi-application-cog-outline</v-icon>
                            </template>
                            <v-list-item-title>Setup</v-list-item-title>
                        </v-list-item>
                    </template>

                    <v-list-item title="Schemes" :to="{ path: '/dashboard/setup/schemes' }"></v-list-item>
                    <v-list-item title="Provider" :to="{ path: '/dashboard/setup/provider' }"></v-list-item>
                    <v-list-item title="Api" :to="{ path: '/dashboard/setup/api' }"></v-list-item>
                </v-list-group>

                <v-list-group>
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" class="rounded-lg">
                            <template v-slot:prepend>
                                <v-icon size="18">mdi-account-circle</v-icon>
                            </template>
                            <v-list-item-title>Members</v-list-item-title>
                        </v-list-item>
                    </template>
                    <v-list-item title="Super Admin"></v-list-item>
                    <v-list-item title="Api Partner"></v-list-item>
                    <v-list-item title="Master Distributer"></v-list-item>
                    <v-list-item title="Distributer"></v-list-item>
                    <v-list-item title="DSA"></v-list-item>
                    <v-list-item title="Customer"></v-list-item>
                </v-list-group>

                <v-list-group>
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" class="rounded-lg">
                            <template v-slot:prepend>
                                <v-icon size="18">mdi-account-circle</v-icon>
                            </template>
                            <v-list-item-title>Roles & Permissions</v-list-item-title>
                        </v-list-item>
                    </template>
                    <v-list-item title="Roles" :to="{ path: '/dashboard/usertype/roles' }"></v-list-item>
                    <v-list-item title="Permissions" :to="{ path: '/dashboard/usertype/permission' }"></v-list-item>
                </v-list-group>
            </v-list>

        </v-navigation-drawer>

        <v-app-bar flat style="background-color: #f9f9f9;">
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-app-bar-title>Application</v-app-bar-title>
            <v-spacer></v-spacer>
            <v-menu transition="slide-y-transition">
                <template v-slot:activator="{ props }">
                    <v-btn color="primary" v-bind="props">
                        Slide Y Transition
                    </v-btn>
                </template>
                <v-list>
                  
                </v-list>
            </v-menu>
        </v-app-bar>

        <v-main style="background-color: #f9f9f9;">
            <router-view></router-view>
        </v-main>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        drawer: null,

    }),
    methods: {
        async getprofile() {
            const respose = await axios.get('/user/profile');
            if (respose.data.status == 201) {
                this.$router.push('/');
            }
        }
    },
    created() {
        this.getprofile();
    }
}
</script>

<style>
.active-list-item {
    background-color: #f9fbfc !important;
    font-weight: bold;
    border: 1px solid #f0f0f0 !important;
}
</style>