<template>
    <v-container class="d-flex justify-center align-center" style="height: 100vh;">
        <v-card class="pa-5" width="400">
            <v-card-text v-if="verify_token == null">
                <v-text-field v-model="phone" label="Phone Number" prepend-inner-icon="mdi-phone" type="tel"
                    variant="outlined"></v-text-field>
                <v-btn @click="login" block :loading="loading">Login</v-btn>
            </v-card-text>
            <v-card-text v-else>
                <h4>Verify OTP</h4>
                <div class="ml-n2">
                    <v-otp-input :length="4" v-model="otp"></v-otp-input>
                    <v-btn @click="verifyotp" block :loading="loading">Verify OTP</v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import axios from 'axios';


export default {
    data: () => ({
        phone: null,
        latitude: null,
        longitude: null,
        errorMessage: null,
        loading: false,
        verify_token: null,
        otp: "",
    }),
    methods: {
        getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                    },
                    (error) => {
                        this.errorMessage = error.message;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 10000
                    }
                );
            } else {
                this.errorMessage = "Geolocation is not supported by this browser.";
            }
        },
        async login() {
            // if (this.latitude == null) {
            //     this.getLocation();
            //     this.$notify({
            //         text: 'Allow GeoLocation',
            //         type: 'warn'
            //     });
            //     return false;
            // }
            try {
                this.loading = true;
                const respose = await axios.post('/user/login', {
                    phone: this.phone,
                    latitude: this.latitude,
                    longitude: this.longitude
                });

                this.$notify({
                    text: respose.data.message,
                    type: respose.data.status == 201 ? 'warn' : ''
                });

                if (respose.data.status == 200) {
                    this.verify_token = respose.data.data.verify_token;
                }

            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        async verifyotp() {
            try {
                this.loading = true;
                const respose = await axios.post('/user/verifyotp', {
                    verify_token: this.verify_token,
                    otp: this.otp
                });

                if (respose.data.status == 200) {
                    this.$router.push('/dashboard');
                }

            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },

        async getprofile() {
            const respose = await axios.get('/user/profile');
            if (respose.data.status == 200) {
                this.$router.push('/dashboard');
            }
        }
    },
    created() {
        this.getLocation();
        this.getprofile();

    }
};
</script>