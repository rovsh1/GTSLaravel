<script setup lang="ts">

import { z } from 'zod'

import { requestInitialData } from '~lib/initial-data'

import PricesTable from './PricesTable.vue'

import { Car, Season } from './lib'

const { seasons, services, cars, providerId } = requestInitialData('view-initial-data-service-provider', z.object({
  providerId: z.number(),
  seasons: z.array(z.object({
    id: z.number(),
    provider_id: z.number(),
    date_start: z.string(),
    date_end: z.string(),
  })),
  services: z.array(z.object({
    id: z.number(),
    provider_id: z.number(),
    name: z.string(),
    type: z.number(),
  })),
  cars: z.array(z.object({
    id: z.number(),
    mark: z.string(),
    model: z.string(),
  })),
}))

</script>

<template>
  <PricesTable
    v-for="service in services"
    :key="service.id"
    :header="service.name"
    :cars="cars as Car[]"
    :seasons="seasons as Season[]"
    :provider-id="providerId as number"
    :service-id="service.id"
  />
</template>
