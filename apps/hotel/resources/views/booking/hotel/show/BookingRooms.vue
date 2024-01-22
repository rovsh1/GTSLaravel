<script setup lang="ts">

import { computed, onMounted } from 'vue'

import { storeToRefs } from 'pinia'

import { useCountryStore } from '~resources/store/countries'
import { useCurrencyStore } from '~resources/store/currency'
import GuestsTable from '~resources/views/booking/shared/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import { getConditionLabel } from '~resources/views/booking/shared/lib/constants'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useOrderStore } from '~resources/views/booking/shared/store/order'

import {
  HotelBookingDetails,
  HotelRoomBooking,
  RoomBookingDayPrice,
} from '~api/booking/hotel/details'
import { HotelRate, useHotelRatesAPI } from '~api/hotel/price-rate'
import { Currency } from '~api/models'
import { Guest } from '~api/order/guest'

import { formatPrice } from '~lib/price'

import BootstrapCard from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import BootstrapCardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import EmptyData from '~components/EmptyData.vue'
import InlineIcon from '~components/InlineIcon.vue'

const { getCurrencyByCodeChar } = useCurrencyStore()
const bookingStore = useBookingStore()
const orderStore = useOrderStore()

const bookingDetails = computed<HotelBookingDetails | undefined>(() => bookingStore.booking?.details)

const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.clientPrice.currency.value),
)

const orderGuests = computed<Guest[]>(() => orderStore.guests || [])

const { execute: fetchPriceRates, data: priceRates } = useHotelRatesAPI({})

const { countries } = storeToRefs(useCountryStore())

const getPriceRateName = (id: number): string | undefined =>
  priceRates.value?.find((priceRate: HotelRate) => priceRate.id === id)?.name

const getCheckInTime = (room: HotelRoomBooking) => {
  if (room.details.earlyCheckIn) {
    return getConditionLabel(room.details.earlyCheckIn)
  }

  return `с ${bookingDetails.value?.hotelInfo.checkInTime}`
}

const getCheckOutTime = (room: HotelRoomBooking) => {
  if (room.details.lateCheckOut) {
    return getConditionLabel(room.details.lateCheckOut)
  }

  return `до ${bookingDetails.value?.hotelInfo.checkOutTime}`
}

const getMinDayPrices = (dayPrices: RoomBookingDayPrice[]): number | undefined => {
  const minPrice = dayPrices.length > 0 ? dayPrices.reduce((min, dayPrice) =>
    ((dayPrice.grossValue < min) ? dayPrice.grossValue : min), dayPrices[0].grossValue) : undefined
  return minPrice
}

const getMaxDayPrices = (dayPrices: RoomBookingDayPrice[]): number | undefined => {
  const maxPrice = dayPrices.length > 0 ? dayPrices.reduce((max, dayPrice) =>
    ((dayPrice.grossValue > max) ? dayPrice.grossValue : max), dayPrices[0].grossValue) : undefined
  return maxPrice
}

onMounted(() => {
  fetchPriceRates()
})

</script>

<template>
  <div class="mt-3" />
  <BootstrapCard v-for="(room, index) in bookingDetails?.roomBookings" :key="room.id">
    <div class="d-flex align-items-start">
      <BootstrapCardTitle class="mr-4" :title="`#${index + 1} ${room.roomInfo.name}`" />
    </div>
    <div class="d-flex flex-row gap-4">
      <InfoBlock>
        <template #header>
          <InfoBlockTitle class="mb-3" title="Параметры размещения" />
        </template>
        <table class="table-params">
          <tbody>
            <tr>
              <th>Тип стоимости</th>
              <td>{{ room.details.isResident ? 'Резидент' : 'Не резидент' }}</td>
            </tr>
            <tr>
              <th>Тариф</th>
              <td>{{ getPriceRateName(room.details.rateId) }}</td>
            </tr>
            <tr>
              <th>Скидка</th>
              <td>{{ room.details.discount || 'Не указано' }}</td>
            </tr>
            <tr>
              <th>Время заезда</th>
              <td>{{ getCheckInTime(room) }}</td>
            </tr>
            <tr>
              <th>Время выезда</th>
              <td>{{ getCheckOutTime(room) }}</td>
            </tr>
            <tr>
              <th>Примечание (запрос в отель, ваучер)</th>
              <td>{{ room.details.guestNote }}</td>
            </tr>
          </tbody>
        </table>
        <div v-if="grossCurrency" class="d-flex flex-row justify-content-between w-100 mt-2">
          <span class="prices-information">
            <strong>
              Итого: {{ formatPrice(room.price.grossValue, grossCurrency.sign) }}
            </strong>
            <br>
            <strong class="prices-information-details">
              <span>Цена за ночь: </span>
              <span v-if="getMinDayPrices(room.price.dayPrices) === getMaxDayPrices(room.price.dayPrices)">
                {{ formatPrice(getMinDayPrices(room.price.dayPrices) as number, grossCurrency.sign) }}
              </span>
              <span v-else>
                от {{ formatPrice(getMinDayPrices(room.price.dayPrices) as number, grossCurrency.sign) }}
                до {{ formatPrice(getMaxDayPrices(room.price.dayPrices) as number, grossCurrency.sign) }}
              </span>
              <span
                v-if="room.price.grossDayValue"
                v-tooltip="'Цена за номер выставлена вручную'"
                class="prices-information-details-info"
              >
                <InlineIcon icon="touch_app" class="prices-information-details-info-icon" />
              </span>
            </strong>
          </span>
        </div>
      </InfoBlock>

      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1 align-items-center mb-1">
            <InfoBlockTitle title="Список гостей" />
          </div>
        </template>

        <GuestsTable
          :guest-ids="room.guests.map(guest => guest.id)"
          :order-guests="orderGuests"
          :countries="countries || []"
        />
      </InfoBlock>
    </div>
  </BootstrapCard>

  <div>
    <div>
      <EmptyData v-if="!bookingDetails?.roomBookings.length">
        Забронированные номера отсутствуют
      </EmptyData>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.prices-information {
  .prices-information-details {
    font-weight: 400;
    font-style: italic;

    &>* {
      vertical-align: middle
    }

    .informationIcon {
      width: auto;
      height: 1.4em;
    }

    .prices-information-details-button {
      margin-left: 0.4rem;
      border: none;
      background-color: transparent;
      outline: none;
      cursor: pointer;
    }

    .prices-information-details-info {
      display: inline-block;

      .prices-information-details-info-icon {
        opacity: 0.5;
      }
    }

    .prices-information-details-button-icon,
    .prices-information-details-info-icon {
      font-size: 1.25rem;
    }
  }
}

.pt-card-title {
  margin-top: 0.85rem;
  padding-top: 0;
}
</style>
