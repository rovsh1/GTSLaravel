<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import GuestModal from '~resources/views/hotel-booking/show/components/GuestModal.vue'
import RoomModal from '~resources/views/hotel-booking/show/components/RoomModal.vue'
import RoomPriceModal from '~resources/views/hotel-booking/show/components/RoomPriceModal.vue'
import { getConditionLabel, getGenderName, getRoomStatusName } from '~resources/views/hotel-booking/show/composables/constants'
import { GuestFormData, RoomFormData } from '~resources/views/hotel-booking/show/composables/form'
import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'
import { useOrderStore } from '~resources/views/hotel-booking/show/store/order-currency'

import {
  HotelBookingDetails,
  HotelBookingGuest,
  HotelRoomBooking,
  RoomBookingPrice,
} from '~api/booking/details'
import { updateRoomBookingPrice } from '~api/booking/price'
import { deleteBookingGuest, deleteBookingRoom } from '~api/booking/rooms'
import { CountryResponse, useCountrySearchAPI } from '~api/country'
import { MarkupSettings } from '~api/hotel/markup-settings'
import { HotelRate, useHotelRatesAPI } from '~api/hotel/price-rate'
import { Currency } from '~api/models'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { formatDate } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'

const [isShowRoomModal, toggleRoomModal] = useToggle()
const [isShowGuestModal, toggleGuestModal] = useToggle()
const [isShowRoomPriceModal, toggleRoomPriceModal] = useToggle()

const { bookingID, hotelID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    hotelID: z.number(),
    bookingID: z.number(),
  }),
)

const bookingStore = useBookingStore()
const { fetchBooking, fetchAvailableActions } = bookingStore
const orderStore = useOrderStore()

const bookingDetails = computed<HotelBookingDetails | null>(() => bookingStore.booking)
const markupSettings = computed<MarkupSettings | null>(() => bookingStore.markupSettings)
const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)
const orderCurrency = computed<Currency | undefined>(() => orderStore.currency)
const canChangeRoomPrice = computed<boolean>(() => bookingStore.availableActions?.canChangeRoomPrice || false)

const { execute: fetchPriceRates, data: priceRates } = useHotelRatesAPI({ hotelID })
const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const getPriceRateName = (id: number): string | undefined =>
  priceRates.value?.find((priceRate: HotelRate) => priceRate.id === id)?.name

const getCountryName = (id: number): string | undefined =>
  countries.value?.find((country: CountryResponse) => country.id === id)?.name

const getDefaultGuestForm = () => ({ isAdult: true })

const roomForm = ref<Partial<RoomFormData>>({})
const guestForm = ref<Partial<GuestFormData>>(getDefaultGuestForm())

const editRoomBookingId = ref<number>()
const editGuestIndex = ref<number>()
const handleAddRoomGuest = (roomBookingId: number) => {
  editRoomBookingId.value = roomBookingId
  editGuestIndex.value = undefined
  guestForm.value = getDefaultGuestForm()
  toggleGuestModal(true)
}

const handleEditGuest = (roomBookingId: number, guestIndex: number, guest: HotelBookingGuest): void => {
  editRoomBookingId.value = roomBookingId
  editGuestIndex.value = guestIndex
  guestForm.value = guest
  toggleGuestModal(true)
}

const handleDeleteGuest = async (roomBookingId: number, guestIndex: number): Promise<void> => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить гостя?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteBookingGuest({ bookingID, roomBookingId, guestIndex })
    await fetchBooking()
    toggleClose()
  }
}

const handleAddRoom = (): void => {
  editRoomBookingId.value = undefined
  roomForm.value = {}
  toggleRoomModal()
  fetchAvailableActions()
}

const handleEditRoom = (roomBookingId: number, room: HotelRoomBooking): void => {
  editRoomBookingId.value = roomBookingId
  roomForm.value = room
  roomForm.value.id = room.roomInfo.id
  roomForm.value.status = room.status
  roomForm.value.discount = room.details.discount
  roomForm.value.rateId = room.details.rateId
  roomForm.value.earlyCheckIn = room.details.earlyCheckIn
  roomForm.value.lateCheckOut = room.details.lateCheckOut
  roomForm.value.residentType = room.details.isResident ? 1 : 0
  roomForm.value.note = room.details.guestNote
  toggleRoomModal()
}

const handleDeleteRoom = async (roomBookingId: number): Promise<void> => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить номер?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteBookingRoom({ bookingID, roomBookingId })
    await fetchBooking()
    toggleClose()
  }
}

const editableRoomPrice = ref<RoomBookingPrice>()
const handleEditRoomPrice = (roomBookingId: number, roomPrice: RoomBookingPrice): void => {
  editRoomBookingId.value = roomBookingId
  editableRoomPrice.value = roomPrice
  toggleRoomPriceModal(true)
}

const handleUpdateRoomPrice = async (boPrice: number | undefined | null, hoPrice: number | undefined | null) => {
  toggleRoomPriceModal(false)
  await updateRoomBookingPrice({
    bookingID,
    roomBookingId: editRoomBookingId.value as number,
    boPrice,
    hoPrice,
  })
  fetchBooking()
}

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

const onModalSubmit = () => {
  toggleRoomModal(false)
  toggleGuestModal(false)
  fetchBooking()
}

fetchPriceRates()
fetchCountries()

</script>

<template>
  <RoomModal
    :opened="isShowRoomModal"
    :form-data="roomForm"
    :room-booking-id="editRoomBookingId"
    :hotel-markup-settings="markupSettings"
    @close="toggleRoomModal(false)"
    @submit="onModalSubmit"
  />

  <GuestModal
    v-if="countries && editRoomBookingId !== undefined"
    :opened="isShowGuestModal"
    :room-booking-id="editRoomBookingId"
    :guest-index="editGuestIndex"
    :form-data="guestForm"
    :countries="countries as CountryResponse[]"
    @close="toggleGuestModal(false)"
    @submit="onModalSubmit"
  />

  <RoomPriceModal
    :booking-id="bookingID"
    :room-price="editableRoomPrice"
    :opened="isShowRoomPriceModal"
    @close="toggleRoomPriceModal(false)"
    @submit="({ boPrice, hoPrice }) => handleUpdateRoomPrice(boPrice, hoPrice)"
  />

  <div class="mt-3" />
  <div
    v-for="room in bookingDetails?.roomBookings"
    :key="room.id"
    class="card mt-2 mb-4"
  >
    <div class="card-body">
      <div class="d-flex">
        <h5 class="card-title mr-4">
          {{ room.roomInfo.name }}
        </h5>
        <EditTableRowButton
          v-if="isEditableStatus"
          @edit="handleEditRoom(room.id, room)"
          @delete="handleDeleteRoom(room.id)"
        />
      </div>
      <div class="d-flex flex-row gap-4">
        <div class="w-100 rounded shadow-lg p-4">
          <h6>Параметры размещения</h6>
          <table class="table-params">
            <tbody>
              <tr>
                <th>Статус</th>
                <td>{{ getRoomStatusName(room.status) }}</td>
              </tr>
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

          <div class="mt-2">
            <table class="table">
              <thead>
                <tr>
                  <th />
                  <th class="text-nowrap">Дата</th>
                  <th class="text-nowrap">Итого</th>
                  <th class="text-nowrap">Подробный расчет</th>
                </tr>
              </thead>
              <tbody v-if="orderCurrency">
                <tr v-for="dayPrice in room.price.dayPrices" :key="dayPrice.date">
                  <td>Стоимость брутто</td>
                  <td class="text-nowrap">
                    {{ formatDate(dayPrice.date) }}
                  </td>
                  <td class="text-nowrap">
                    {{ dayPrice.boValue }}
                    <span class="cur">{{ orderCurrency.sign }}</span>
                  </td>
                  <td class="text-nowrap">{{ dayPrice.boFormula }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!--          <div v-if="orderCurrency" class="conditions">-->
          <!--            <span class="condition-item">Ранний заезд  - <code>????</code>-->
          <!--              <span class="cur">{{ orderCurrency.sign }}</span>-->
          <!--            </span>-->
          <!--            <span class="condition-item">Поздний выезд  - <code>????</code>-->
          <!--              <span class="cur">{{ orderCurrency.sign }}</span>-->
          <!--            </span>-->
          <!--          </div>-->
          <div v-if="orderCurrency" class="d-flex flex-row justify-content-between w-100 mt-2">
            <strong>Итого: {{ room.price.boValue }}
              <span class="cur">{{ orderCurrency.sign }}</span>
            </strong>
            <a
              v-if="canChangeRoomPrice"
              href="#"
              @click.prevent="handleEditRoomPrice(room.id, room.price)"
            >
              Изменить цену номера
            </a>
          </div>
          <span v-if="room.price.boDayValue" class="text-muted">(цена за номер выставлена вручную)</span>
        </div>
        <div class="w-100 rounded shadow-lg p-4">
          <h6>Список гостей</h6>
          <a v-if="isEditableStatus" href="#" @click.prevent="handleAddRoomGuest(room.id)">
            <i class="icon">add</i>
          </a>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>№</th>
                <th class="column-text">ФИО</th>
                <th class="column-text">Пол</th>
                <th class="column-text">Гражданство</th>
                <th class="column-text">Тип</th>
                <th class="column-text" />
                <th />
              </tr>
            </thead>
            <tbody>
              <tr v-for="(guest, guestIdx) in room.guests" :key="guestIdx">
                <td>{{ guestIdx + 1 }}</td>
                <td>{{ guest.fullName }}</td>
                <td>{{ getCountryName(guest.countryId) }}</td>
                <td>{{ getGenderName(guest.gender) }}</td>
                <td>{{ guest.isAdult ? 'Взрослый' : 'Ребенок' }}</td>
                <td class="column-edit">
                  <EditTableRowButton
                    v-if="isEditableStatus"
                    @edit="handleEditGuest(room.id, guestIdx as number, guest)"
                    @delete="handleDeleteGuest(room.id, guestIdx as number)"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div>
    <button
      v-if="isEditableStatus"
      type="button"
      class="btn btn-primary"
      @click="handleAddRoom"
    >
      Добавить номер
    </button>
  </div>
</template>
