<script setup lang="ts">

import { z } from 'zod'

import { Airport, Currency, Season } from '~api/models'

import { requestInitialData } from '~lib/initial-data'

import PricesTable from './PricesTable.vue'

const { seasons, services, airports, providerId, currencies } = requestInitialData('view-initial-data-service-provider', z.object({
  providerId: z.number(),
  seasons: z.array(z.object({
    id: z.number(),
    number: z.string(),
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
  airports: z.array(z.object({
    id: z.number(),
    name: z.string(),
    city_id: z.number(),
    code: z.string(),
    city_name: z.string(),
  })),
  currencies: z.array(z.object({
    id: z.number(),
    code_num: z.number(),
    code_char: z.string(),
    sign: z.string(),
  })),
}))

</script>

<template>
  <PricesTable
    v-for="service in services"
    :key="service.id"
    :header="service.name"
    :airports="airports as Airport[]"
    :seasons="seasons as Season[]"
    :currencies="currencies as Currency[]"
    :provider-id="providerId as number"
    :service-id="service.id"
  />
</template>
