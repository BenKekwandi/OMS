<template>
    <v-dialog v-model="dialog" max-width="1000px">
        <v-card>

            <v-card-title class="d-flex justify-space-between align-center">
                <span class="text-h5">Create New Shipment</span>
                <v-btn icon="mdi-close" variant="text" @click="console.log('Close Shipment')"></v-btn>
            </v-card-title>
            <v-divider class="mx-4"></v-divider>

            <v-card-text>
                <v-row>
                    <v-col cols="6">
                        <v-autocomplete label="Shipping Service" v-model="shipment.shipping_service"
                            :items="['Service 1', 'Service 2', 'Service 3']"
                            prepend-inner-icon="mdi-domain"></v-autocomplete>
                        <v-autocomplete label="Shipping Account" v-model="shipment.shipping_account"
                            :items="['Account 1', 'Account 2', 'Account 3']"
                            prepend-inner-icon="mdi-account-circle"></v-autocomplete>
                        <v-text-field label="Pick up Time" v-model="shipment.pickup_time"
                            prepend-inner-icon="mdi-timer-outline" type="time"></v-text-field>
                        <v-menu>
                            <template v-slot:activator="{ props }">
                                <v-text-field readonly label="Deadline" prepend-inner-icon="mdi-calendar"
                                    v-bind="props"></v-text-field>
                            </template>
                            <v-date-picker v-model="shipment.deadline" max="2030-12-31"
                                min="2023-01-01"></v-date-picker>
                        </v-menu>
                    </v-col>
                    <v-col cols="6">
                        <v-row>
                            <v-col cols="6">
                                <p>Shipping Type</p>
                            </v-col>
                            <v-col cols="6">
                                <v-radio-group inline v-model="shipment.shipping_type">
                                    <v-radio label="Incoming" value="Incoming" color="indigo"></v-radio>
                                    <v-radio label="Outgoing" value="Outgoing" color="indigo"></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="6">
                                <p>Automatic Ship</p>
                            </v-col>
                            <v-col cols="6">
                                <v-radio-group inline v-model="shipment.automatic_ship">
                                    <v-radio label="Enabled" value="Enabled"></v-radio>
                                    <v-radio label="Disabled" value="Disabled"></v-radio>
                                </v-radio-group>
                            </v-col>
                        </v-row>

                        <v-select label="Shipping from" v-model="shipment.ship_from"
                            :items="['Location from #1', 'Location from #2', 'Location from #3']"
                            prepend-inner-icon="mdi-domain"></v-select>

                        <v-select label="Shipping to" v-model="shipment.ship_to"
                            :items="['Location to #1', 'Location to #2', 'Location to #3']"
                            prepend-inner-icon="mdi-domain"></v-select>
                    </v-col>
                </v-row>
                <v-row>
                    <div class="d-flex">
                        <span class="text-h5">From</span>
                        <v-btn icon="mdi-chevron-down" variant="text" @click="console.log('From Details')"></v-btn>
                    </div>
                </v-row>
                <v-row>
                    <v-col cols="6">
                        <v-text-field label="Company Name" v-model="shipment.details.from.company_name"></v-text-field>
                        <v-text-field label="Contact Name" v-model="shipment.details.from.contact_name"
                            :rules="[rules.required]"></v-text-field>
                        <v-text-field label="Email" type="email" v-model="shipment.details.from.email"></v-text-field>
                        <v-text-field label="Phone" v-model="shipment.details.from.phone"></v-text-field>
                        <v-text-field label="Fax" v-model="shipment.details.from.fax"></v-text-field>
                        <v-text-field label="Tax ID" v-model="shipment.details.from.tax_id"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field label="Street 1" v-model="shipment.details.from.street_1"
                            :rules="[rules.required]"></v-text-field>
                        <v-text-field label="Street 2" v-model="shipment.details.from.street_2"></v-text-field>
                        <v-text-field label="Street 3" v-model="shipment.details.from.street_3"></v-text-field>
                        <v-text-field label="City" v-model="shipment.details.from.city"
                            :rules="[rules.required]"></v-text-field>
                        <v-text-field label="State" v-model="shipment.details.from.state"></v-text-field>
                        <v-autocomplete label="Country" v-model="shipment.details.from.country"
                            :items="['Country 1', 'Country 2', 'Country 3']" :rules="[rules.required]"></v-autocomplete>
                        <v-text-field label="Postal Code" v-model="shipment.details.from.postal_code"></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <div class="d-flex">
                        <span class="text-h5">To</span>
                        <v-btn icon="mdi-chevron-down" variant="text" @click="console.log('To Details')"></v-btn>
                    </div>
                </v-row>

                <v-row>
                    <v-col cols="6">
                        <v-text-field label="Company Name" v-model="shipment.details.to.company_name"></v-text-field>
                        <v-text-field label="Contact Name" v-model="shipment.details.to.contact_name"
                            :rules="[rules.required]"></v-text-field>
                        <v-text-field label="Email" type="email" v-model="shipment.details.to.email"></v-text-field>
                        <v-text-field label="Phone" v-model="shipment.details.to.phone"></v-text-field>
                        <v-text-field label="Fax" v-model="shipment.details.to.fax"></v-text-field>
                        <v-text-field label="Tax ID" v-model="shipment.details.to.tax_id"></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field label="Street 1" v-model="shipment.details.to.street_1"
                            :rules="[rules.required]"></v-text-field>
                        <v-text-field label="Street 2" v-model="shipment.details.to.street_2"></v-text-field>
                        <v-text-field label="Street 3" v-model="shipment.details.to.street_3"></v-text-field>
                        <v-text-field label="City" v-model="shipment.details.to.city"
                            :rules="[rules.required]"></v-text-field>
                        <v-text-field label="State" v-model="shipment.details.to.state"></v-text-field>
                        <v-autocomplete label="Country" v-model="shipment.details.to.country"
                            :items="['Country 1', 'Country 2', 'Country 3']" :rules="[rules.required]"></v-autocomplete>
                        <v-text-field label="Postal Code" v-model="shipment.details.to.postal_code"></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions class="mx-2 my-4">
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <v-btn class="px-4" color="blue-darken-1" variant="elevated"
                    @click="console.log('Create Shipment')">Create
                    Shipment</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { storeToRefs } from "pinia";
import format from "date-fns/format";
import { defineProps, toRefs, ref, watch } from "vue";

// const { collection, errors, res, loading } = storeToRefs(orderShipmentStore);

const props = defineProps({ dialog: Boolean })
const { dialog } = toRefs(props)

const emit = defineEmits(['close', 'save'])

const shipmentDeadline = ref(null)


// Watch for changes in shipmentDeadline and update the expense date
watch(
    () => shipmentDeadline.value,
    (val) => {
        shipment.value.deadline = format(val, "do MMM yyyy");
    }
);

const shipment = ref({
    shipping_service: '',
    shipping_account: '',
    pickup_time: '',
    deadline: null,
    shipping_type: '',
    automatic_ship: '',
    ship_from: '',
    ship_to: '',
    details: {
        from: {
            company_name: '',
            contact_name: '',
            email: '',
            phone: '',
            fax: '',
            tax_id: '',

            street_1: '',
            street_2: '',
            street_3: '',
            city: '',
            state: '',
            country: '',
            postal_code: ''
        },
        to: {
            company_name: '',
            contact_name: '',
            email: '',
            phone: '',
            fax: '',
            tax_id: '',

            street_1: '',
            street_2: '',
            street_3: '',
            city: '',
            state: '',
            country: '',
            postal_code: ''
        }
    }
});

const defaultShipment = ref({
    shipping_service: '',
    shipping_account: '',
    pickup_time: '',
    deadline: null,
    shipping_type: '',
    automatic_ship: '',
    ship_from: '',
    ship_to: '',
    details: {
        from: {
            company_name: '',
            contact_name: '',
            email: '',
            phone: '',
            fax: '',
            tax_id: '',

            street_1: '',
            street_2: '',
            street_3: '',
            city: '',
            state: '',
            country: '',
            postal_code: ''
        },
        to: {
            company_name: '',
            contact_name: '',
            email: '',
            phone: '',
            fax: '',
            tax_id: '',

            street_1: '',
            street_2: '',
            street_3: '',
            city: '',
            state: '',
            country: '',
            postal_code: ''
        }
    }
})

// Close dialog
const close = () => {
    emit('close')
    shipment.value = Object.assign({}, defaultShipment)
}

// Function to upload expense data
const uploadExpense = async () => {
    // Data object most welcome here  
    await orderExpenseStore.addItemHandler(shipment.value);
    if (res.value) {
        emit("save", res.value.message, 'success')
        expense.value = Object.assign({}, defaultShipment.value)
    } else {
        emit("save", errors.value, "error");
    }
    res.value = null
}

// Functions for the LogisticsDatatable

/*
    const dialogShipment = ref(false);

    const openNewExpense = (item) => {
        order.value = Object.assign({}, item);
        dialogShipment.value = true;
    }

    const saveShipment = (message, status) => {
        if (status === "success") {
            emit("initialize");
            emit("alert", message, status);
            closeExpense();
        } else {
            emit("alert", message, status);
        }
    }

    function closeShipment() {
        dialogShipment.value = false;
        nextTick(() => {
            order.value = {};
            errors.value = {};
        });
    }

    <AddShipmentDialog 
        :dialog="dialogShipment" 
        @close="closeShipment" 
        @save="saveShipment" />

    
    Respective store and api are required, then done    
*/

</script>