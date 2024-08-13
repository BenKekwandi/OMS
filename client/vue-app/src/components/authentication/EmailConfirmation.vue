<template>
    <div>
        <v-card class="pa-8 rounded-xl" max-width="400" height="400" variant="elevated" elevation="6">
            <div class="d-flex justify-center">
                <img src="@/assets/img/Successmark.svg" />
            </div>

            <br />
            <v-card-title style="color: #293462;" class="py-6 text-center font-weight-bold">
                Email sent successfully
            </v-card-title>
            <v-card-subtitle class="text-center ">Email has been sent successfully. <br /> Check your
                inbox</v-card-subtitle>
            <br />


            <div class="my-6 d-flex justify-center"> <v-btn :to="{ name: 'login' }" type="submit" elevation="4"
                    color="#00ADB5">Login</v-btn>
            </div>
        </v-card>

    </div>
</template>