<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-card flat class="rounded-lg" style="border:1px solid #f0f0f0; background-color:#fff">
                    <v-card-text class="d-flex align-center justify-space-between">
                        <h4>Scheme Commission</h4>
                        <v-btn @click="update" class="text-none" color="primary" size="small"
                            :loading="loading">Update</v-btn>
                    </v-card-text>
                    <v-card-text>
                        <v-table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>User Commission</th>
                                    <th>Parent Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in apiData" :key="i">
                                    <td>{{ item.name }}</td>
                                    <td>
                                        <v-autocomplete variant="outlined" density="compact"
                                            :items="['flat', 'percentage']" v-model="item.com_type" hide-details>
                                        </v-autocomplete>
                                    </td>
                                    <td><v-text-field variant="outlined" density="compact" v-model="item.com_value"
                                            hide-details></v-text-field></td>
                                    <td><v-text-field variant="outlined" density="compact"
                                            v-model="item.com_parentvalue" hide-details></v-text-field></td>
                                </tr>
                            </tbody>
                        </v-table>

                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";

export default {
    data: () => ({
        apiData: [],
        loading: false,
    }),
    methods: {
        async update() {
            try {
                this.loading = true;
                const response = await axios.post(`/scheme/schemecommissionupdate`, this.apiData);
                console.log(response.data)
                this.$notify({
                    text: response.data.message,
                    type: 'success'
                });
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false
            }
        },
        async getData() {
            const option1 = this.$route.params.option1;
            const option2 = this.$route.params.option2;
            const response = await axios.get(`/scheme/schemecommission/${option1}/${option2}`);
            this.apiData = response.data?.data;
            console.log(response.data);
        }
    },
    mounted() {
        this.getData();
    }
}

</script>