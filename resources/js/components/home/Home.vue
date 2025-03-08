<template>
    <v-container>
        <v-row>
            <v-col cols="8">
                <v-card flat style="border:1px solid #f0f0f0;" class="rounded-lg">
                    <v-card-text class="d-flex justify-space-between align-center">
                        <h5 class="mb-0" style="font-weight: bold;">Statics</h5>
                        <v-btn-toggle v-model="filter" divided variant="outlined" size="small" class="rounded-pill"
                            style="border:1px solid #f0f0f0; height:auto">
                            <v-btn size="small" class="text-none" value="Day" style="height: 1;">Day</v-btn>
                            <v-btn size="small" class="text-none" value="Week" style="height: 1;">Week</v-btn>
                            <v-btn size="small" class="text-none" value="Month" style="height: 1;">Month</v-btn>
                            <v-btn size="small" class="text-none" value="Year" style="height: 1;">Year</v-btn>
                        </v-btn-toggle>
                    </v-card-text>
                    <v-card-text class="pt-0">
                        <v-row class="mb-4">
                            <v-col cols="12" md="6">
                                <v-card flat style="border:1px solid #f0f0f0;background-color:#f9fbfc"
                                    class="rounded-lg">
                                    <v-card-text>
                                        <h5 style="font-weight: bold; color:#7c9cb7">Income</h5>
                                        <h2 class="text-h5 mb-0" style="color: #2f485f;">₹0.00</h2>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-card flat style="border:1px solid #f0f0f0;background-color:#f9fbfc"
                                    class="rounded-lg">
                                    <v-card-text>
                                        <h5 style="font-weight: bold; color:#7c9cb7">Outcome</h5>
                                        <h2 class="text-h5 mb-0" style="color: #2f485f;">₹0.00</h2>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>

                        <BarChart id="my-chart-id"  :options="chartOptions" :chart-data="chartData" />
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="4">
                <v-card flat style="border:1px solid #f0f0f0;" class="rounded-lg">
                    <v-card-text>

                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { BarChart } from 'vue-chart-3';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, BarController } from 'chart.js';
ChartJS.register(BarController, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
    components: { BarChart },
    data() {
        return {
            filter: 'Year',
            chartData: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Orders',
                        backgroundColor: 'green',
                        data: [2, 4, 7, 4, 6, 10, 13, 9, 15, 20, 16, 14]
                    },
                    {
                        label: 'Visitor',
                        backgroundColor: '#f3f3f3',
                        data: [203, 193, 200, 244, 197, 483, 294, 474, 742, 874, 468, 345]
                    }
                ]
            },
            chartOptions: {
                responsive: true
            }
        };
    }
};
</script>