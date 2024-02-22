<script setup lang="ts">
import { computed } from 'vue'

import RequestBlock from '~resources/views/booking/shared/components/RequestBlock.vue'
import { getHumanRequestType } from '~resources/views/booking/shared/lib/constants'
import { useBookingRequestStore } from '~resources/views/booking/shared/store/request'

import { BookingRequest } from '~api/booking/request'

import { formatDateTime } from '~helpers/date'

const requestStore = useBookingRequestStore()
const bookingRequests = computed<BookingRequest[] | null>(() => requestStore.groupedRequests)

</script>

<template>
  <div class="reservation-requests">
    <div
      v-for="bookingRequest in bookingRequests"
      :key="bookingRequest.id"
      class="d-flex flex-row justify-content-between w-100 py-1"
    >
      <div>
        Запрос на {{ getHumanRequestType(bookingRequest.type) }}
        <span class="date align-left ml-1">от
          {{ formatDateTime(bookingRequest.dateCreate) }}</span>
      </div>
      <a href="#" class="btn-download" @click.prevent="requestStore.downloadDocument(bookingRequest.id)">Скачать</a>
    </div>
  </div>

  <div v-if="!bookingRequests?.length">
    <RequestBlock
      :show-button="false"
      text="Запросы ещё не отправлялись"
    />
  </div>
</template>
