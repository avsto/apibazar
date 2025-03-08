<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-card flat class="rounded-lg" style="border:1px solid #f0f0f0; background-color:#fff">
                    <v-card-text class="d-flex align-center justify-space-between">
                        <h4>Providers Management</h4>
                        <v-btn @click="add" class="text-none" color="primary" size="small">Add New</v-btn>
                    </v-card-text>
                    <v-card-text>
                        <v-data-table-server v-model:items-per-page="itemsPerPage" :headers="headers"
                            :items="serverItems" :items-length="totalItems" :loading="loading" :search="search"
                            item-value="name" @update:options="loadItems" show-select>
                            <template v-slot:item.status="{ item }">
                                <div class="text-right" style="max-width: 44px; display: block; margin-left: auto;">
                                    <v-switch hide-details :model-value="item.status == 1 ? true : false"></v-switch>
                                </div>
                            </template>
                            <template v-slot:item.action="{ item }">
                                <v-menu transition="scale-transition">
                                    <template v-slot:activator="{ props }">
                                        <v-btn color="primary" v-bind="props" size="small"
                                            icon="mdi-dots-vertical"></v-btn>
                                    </template>
                                    <v-list nav>

                                        <v-list-item style="min-height: 20px;" @click="edit(item)">
                                            <v-list-item-title><v-icon>mdi-pencil-outline</v-icon> Edit
                                            </v-list-item-title>
                                        </v-list-item>
                                        <v-list-item style="min-height: 20px;">
                                            <v-list-item-title><v-icon>mdi-delete-outline</v-icon> Delete
                                            </v-list-item-title>
                                        </v-list-item>

                                    </v-list>
                                </v-menu>
                            </template>
                        </v-data-table-server>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>

    <v-navigation-drawer v-model="drawer" temporary persistent location="right" rail rail-width="500">
        <v-card flat>
            <v-card-text class="pa-5">
                <v-row>
                    <v-col cols="12 d-flex align-center">
                        <div>
                            <v-btn @click="drawer = false" variant="tonal" icon="mdi-arrow-left" size="small"></v-btn>
                        </div>
                        <div class="ml-4">
                            <h5>Api Details</h5>
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.name" label="Name"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.image" label="image"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.option1" label="option1"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.option2" label="option2"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.option3" label="option3"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.option4" label="option4"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.option5" label="option5"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field hide-details variant="outlined" v-model="dataString.option6" label="option6"
                            density="compact"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete hide-details variant="outlined" :items="apioption" item-title="name"
                            item-value="id" v-model="dataString.api_id" label="api_id"
                            density="compact"></v-autocomplete>
                    </v-col>
                    <v-col cols="6">
                        <v-autocomplete hide-details variant="outlined" :items="services" item-title="name"
                            item-value="code" v-model="dataString.type" label="type" density="compact"></v-autocomplete>
                    </v-col>
                    <v-col cols="6">
                        <v-btn @click="addandupdateapi" :loading="loading">Submit</v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-navigation-drawer>
</template>

<script>
import axios from "axios";

export default {
    data: () => ({

        drawer: false,

        services: [
            { 'code': 'aeps', 'name': 'Aeps' },
            { 'code': 'payout', 'name': 'Payout' },
            { 'code': 'fund', 'name': 'Fund' },
            { 'code': 'mobile', 'name': 'Mobile' },
            { 'code': 'dth', 'name': 'DTH' },
            { 'code': 'educationfee', 'name': 'EDUCATIONAL FEES' },
            { 'code': 'loan', 'name': 'LOAN' },
            { 'code': 'retail', 'name': 'RETAIL' },
            { 'code': 'insurance', 'name': 'INSURANCE' },
            { 'code': 'gas', 'name': 'GAS' },
            { 'code': 'cable', 'name': 'CABLE' },
            { 'code': 'mobilepotpaid', 'name': 'MOBILE POSTPAID' },
            { 'code': 'broadbandpostpaid', 'name': 'BROADBAND POSTPAID' },
            { 'code': 'lpggas', 'name': 'LPG GAS' },
            { 'code': 'landlinepostpaid', 'name': 'LANDLINE POSTPAID' },
            { 'code': 'water', 'name': 'WATER' },
            { 'code': 'fastag', 'name': 'FASTAG' },
            { 'code': 'municipaltaxes', 'name': 'MUNICIPAL TAXES' },
            { 'code': 'entertaiment', 'name': 'ENTERTAINMENT' },
            { 'code': 'electricity', 'name': 'ELECTRICITY' },
            { 'code': 'education', 'name': 'EDUCATION' },
            { 'code': 'travelsub', 'name': 'TRAVEL-SUB' },
            { 'code': 'housingsociety', 'name': 'HOUSING SOCIETY' },
            { 'code': 'subscription', 'name': 'SUBSCRIPTION' },
            { 'code': 'hospital', 'name': 'HOSPITAL' },
            { 'code': 'creditcard', 'name': 'CREDIT CARD' },
            { 'code': 'challan', 'name': 'CHALLAN' },
            { 'code': 'municipalservices', 'name': 'MUNICIPAL SERVICES' },
            { 'code': 'transitcard', 'name': 'TRANSIT CARD' },
            { 'code': 'clubsandassociations', 'name': 'CLUBS AND ASSOCIATIONS' },
            { 'code': 'metro', 'name': 'METRO' },
            { 'code': 'apartment', 'name': 'APARTMENT' },
            { 'code': 'recurringdeposit', 'name': 'RECURRING DEPOSIT' },
        ],
        apioption: [],

        dataString: {
            id: null,
            name: null,
            image: null,
            option1: null,
            option2: null,
            option3: null,
            option4: null,
            option5: null,
            option6: null,
            api_id: null,
            type: null,
        },

        headers: [
            { title: 'name', align: 'start', sortable: false, key: 'name' },
            { title: 'type', key: 'type', align: 'end' },
            { title: 'Status', key: 'status', align: 'end' },
            { title: 'Manage', key: 'action', align: 'end', sortable: false, },
        ],
        serverItems: [],
        totalItems: 0,
        itemsPerPage: 10,
        search: '',
        loading: false,

    }),
    methods: {
        async add() {
            this.drawer = true;
            this.dataString.id = null;
            this.dataString.name = null;
            this.dataString.image = null;
            this.dataString.option1 = null;
            this.dataString.option2 = null;
            this.dataString.option3 = null;
            this.dataString.option4 = null;
            this.dataString.option5 = null;
            this.dataString.option6 = null;
            this.dataString.api_id = null;
            this.dataString.type = null;
        },
        async edit(item) {
            this.drawer = true;
            this.dataString.id = item.id;
            this.dataString.name = item.name;
            this.dataString.image = item.image;
            this.dataString.option1 = item.option1;
            this.dataString.option2 = item.option2;
            this.dataString.option3 = item.option3;
            this.dataString.option4 = item.option4;
            this.dataString.option5 = item.option5;
            this.dataString.option6 = item.option6;
            this.dataString.api_id = item.api_id;
            this.dataString.type = item.type;
        },
        async addandupdateapi() {
            try {
                this.loading = true;
                const response = await axios.post('/provider/add_and_update', this.dataString);
                console.log(response.data);

                if (response.data.status == 200) {
                    this.loadItems({ page: 1, itemsPerPage: this.itemsPerPage, sortBy: 'name' })
                }
                this.$notify({
                    text: response.data.message,
                    type: 'success'
                });

            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
                this.drawer = false;
            }
        },
        async loadItems({ page = 1, itemsPerPage = 10, sortBy = '' }) {
            this.loading = true;
            try {
                const response = await axios.get('/provider/get', {
                    params: { page, per_page: itemsPerPage, sort_by: sortBy }
                });

                if (response.data && response.data.data) {
                    const { data } = response.data;
                    this.serverItems = data.data || [];
                    this.totalItems = data.total || 0;
                    this.itemsPerPage = data.per_page || itemsPerPage;
                } else {
                    console.warn("Unexpected response structure", response.data);
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            } finally {
                this.loading = false;
            }
        },
        async getApi() {
            try {
                const response = await axios.get('/apidata/getAll');
                this.apioption = response.data?.data;
            } catch (error) {

            }
        }
    },
    mounted() {
        this.getApi()
    }
};
</script>
