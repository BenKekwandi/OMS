<template>
   <v-card>
      <v-card-text>
         <v-row no-gutters>
            <v-col cols="3">
               <v-list-subheader class="text-body-1">Order Confirmation Deadline / days</v-list-subheader> 
            </v-col>
            <v-col cols="9">
               <v-text-field variant="outlined" density="compact"></v-text-field>
            </v-col>
         </v-row>
         <v-row no-gutters>
            <v-col cols="3">
               <v-list-subheader class="text-body-1">Offer expiry period in shop / days</v-list-subheader> 
            </v-col>
            <v-col cols="9">
               <v-text-field variant="outlined" density="compact"></v-text-field>
            </v-col>
         </v-row>
         <v-row no-gutters>
            <v-col cols="3">
               <v-list-subheader class="text-body-1">Offer expiry period on orders / days</v-list-subheader> 
            </v-col>
            <v-col cols="9">
               <v-text-field variant="outlined" density="compact"></v-text-field>
            </v-col>
         </v-row>

         <v-row no-gutters>
            <v-col cols="3">
               <v-list-subheader class="text-body-1">Limit for net price adjustment / euro</v-list-subheader> 
            </v-col>
            <v-col cols="9">
               <v-text-field variant="outlined" density="compact"></v-text-field>
            </v-col>
         </v-row>

         <v-row no-gutters>
            <v-col cols="3">
               <v-list-subheader class="text-body-1">Finalization period / days</v-list-subheader> 
            </v-col>
            <v-col cols="9">
               <v-text-field variant="outlined" density="compact"></v-text-field>
            </v-col>
         </v-row>

         <v-row no-gutters>
            <v-col cols="3">
               <v-list-subheader class="text-body-1">Offer made timout / days </v-list-subheader> 
            </v-col>
            <v-col cols="9">
               <v-text-field variant="outlined" density="compact"></v-text-field>
            </v-col>
         </v-row>
      </v-card-text>

      <v-card-actions>
         <v-btn >Update settings</v-btn>
      </v-card-actions>
   </v-card>
</template>