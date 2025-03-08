<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <v-card flat class="rounded-lg" style="border:1px solid #f0f0f0; background-color:#fff">
                    <v-card-text class="d-flex align-center justify-space-between">
                        <h4>schemes</h4>
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
                                    <v-list nav height="200px">

                                        <v-list-item style="min-height: 20px;" @click="edit(item)">
                                            <v-list-item-title><v-icon>mdi-pencil-outline</v-icon> Edit
                                            </v-list-item-title>
                                        </v-list-item>
                                        <v-list-item style="min-height: 20px;" @click="remove(item.id)">
                                            <v-list-item-title><v-icon>mdi-delete-outline</v-icon> Delete
                                            </v-list-item-title>
                                        </v-list-item>


                                        <v-list-subheader style="min-height: 20px;">API Commission</v-list-subheader>
                                        <v-list-item style="min-height: 20px;" v-for="service in services"
                                            :key="service.code"
                                            :to="'/dashboard/setup/schemeCommision/' + item.id + '/' + service.code">
                                            <v-list-item-title><v-icon>mdi-pencil-outline</v-icon>
                                                {{ service.name }}</v-list-item-title>
                                        </v-list-item>

                                        <v-list-subheader style="min-height: 20px;">API Charge</v-list-subheader>
                                        <v-list-item
                                            style="min-height: 20px;"><v-list-item-title><v-icon>mdi-pencil-outline</v-icon>
                                                PayU Payment
                                                Gateway</v-list-item-title></v-list-item>
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
                            <h5>Schemes</h5>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field hide-details variant="outlined" v-model="dataString.name" label="Enter Name"
                            density="compact"></v-text-field>
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

        dataString: {
            id: null,
            name: null,
        },
        services: [
            { 'code': 'aeps', 'name': 'Aeps' },
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

        headers: [
            { title: 'Name', key: 'name', align: 'start' },
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
        },
        async edit(item) {
            this.drawer = true;
            this.dataString.id = item.id;
            this.dataString.name = item.name;
        },
        async remove(id) {
            try {

                const response = await axios.post('/scheme/remove/' + id,);
                if (response.data.status == 200) {
                    this.loadItems({ page: 1, itemsPerPage: 10, sortBy: '' })
                }
                this.$notify({
                    text: response.data.message,
                    type: 'success'
                });

            } catch (error) {
                console.log(error);
            } finally {

            }
        },
        async addandupdateapi() {
            try {
                this.loading = true;
                const response = await axios.post('/scheme/addupdate', this.dataString);

                if (response.data.status == 200) {
                    this.loadItems({ page: 1, itemsPerPage: 10, sortBy: '' })
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
                const response = await axios.get('/scheme/get', {
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
        }
    }
};
</script>
