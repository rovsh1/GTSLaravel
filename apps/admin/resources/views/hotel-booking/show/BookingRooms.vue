<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import GuestModal from '~resources/views/hotel-booking/show/components/GuestModal.vue'
import RoomModal from '~resources/views/hotel-booking/show/components/RoomModal.vue'
import { getConditionLabel, getGenderName, getRoomStatusName } from '~resources/views/hotel-booking/show/constants'
import { GuestFormData, RoomFormData } from '~resources/views/hotel-booking/show/form'
import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'

import { HotelBookingDetails, HotelBookingGuest, HotelRoomBooking } from '~api/booking/details'
import { deleteBookingRoom } from '~api/booking/rooms'
import { CountryResponse, useCountrySearchAPI } from '~api/country'
import { MarkupSettings } from '~api/hotel/markup-settings'
import { HotelRate, useHotelRatesAPI } from '~api/hotel/price-rate'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const [isShowRoomModal, toggleRoomModal] = useToggle()
const [isShowGuestModal, toggleGuestModal] = useToggle()

const { bookingID, hotelID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    hotelID: z.number(),
    bookingID: z.number(),
  }),
)

const bookingStore = useBookingStore()
const { fetchBookingDetails } = bookingStore
fetchBookingDetails()
const bookingDetails = computed<HotelBookingDetails | null>(() => bookingStore.bookingDetails)
const markupSettings = computed<MarkupSettings | null>(() => bookingStore.markupSettings)
const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const { execute: fetchPriceRates, data: priceRates } = useHotelRatesAPI({ hotelID })
const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const onModalSubmit = () => {
  toggleRoomModal(false)
  toggleGuestModal(false)
  fetchBookingDetails()
}

const getPriceRateName = (id: number): string | undefined =>
  priceRates.value?.find((priceRate: HotelRate) => priceRate.id === id)?.name

const getCountryName = (id: number): string | undefined =>
  countries.value?.find((country: CountryResponse) => country.id === id)?.name

const roomForm = ref<Partial<RoomFormData>>({})
const guestForm = ref<Partial<GuestFormData>>({})

const editRoomIndex = ref<number>()
const editGuestIndex = ref<number>()
const handleAddRoomGuest = (roomIndex: number) => {
  editRoomIndex.value = roomIndex
  editGuestIndex.value = undefined
  guestForm.value = {}
  toggleGuestModal()
}

const handleEditGuest = (roomIndex: number, guestIndex: number, guest: HotelBookingGuest): void => {
  editRoomIndex.value = roomIndex
  editGuestIndex.value = guestIndex
  guestForm.value = guest
  toggleGuestModal()
}

const handleAddRoom = (): void => {
  editRoomIndex.value = undefined
  roomForm.value = {}
  toggleRoomModal()
}

const handleEditRoom = (roomIndex: number, room: HotelRoomBooking): void => {
  editRoomIndex.value = roomIndex
  roomForm.value = room
  roomForm.value.id = room.roomInfo.id
  roomForm.value.status = room.status
  roomForm.value.roomCount = room.details.roomCount
  roomForm.value.discount = room.details.discount
  roomForm.value.rateId = room.details.rateId
  roomForm.value.earlyCheckIn = room.details.earlyCheckIn
  roomForm.value.lateCheckOut = room.details.lateCheckOut
  roomForm.value.residentType = room.details.isResident ? 1 : 0
  roomForm.value.note = room.details.guestNote
  toggleRoomModal()
}

const handleDeleteRoom = async (roomIndex: number): Promise<void> => {
  const { result: isApproved, toggleLoading, toggleClose } = await showConfirmDialog('Удалить номер?', 'btn-danger')
  if (isApproved) {
    toggleLoading()
    await deleteBookingRoom({ bookingID, roomIndex })
    await fetchBookingDetails()
    toggleClose()
  }
}

const getCheckInTime = (room: HotelRoomBooking) => {
  if (room.details.earlyCheckIn) {
    return getConditionLabel(room.details.earlyCheckIn)
  }

  // @todo тут будут дефолтные настройки из отеля
  return ''
}

const getCheckOutTime = (room: HotelRoomBooking) => {
  if (room.details.lateCheckOut) {
    return getConditionLabel(room.details.lateCheckOut)
  }

  // @todo тут будут дефолтные настройки из отеля
  return ''
}

fetchPriceRates()
fetchCountries()

</script>

<template>
  <RoomModal
    :opened="isShowRoomModal"
    :form-data="roomForm"
    :room-index="editRoomIndex"
    :hotel-markup-settings="markupSettings"
    @close="toggleRoomModal(false)"
    @submit="onModalSubmit"
  />

  <GuestModal
    v-if="countries && editRoomIndex !== undefined"
    :opened="isShowGuestModal"
    :room-index="editRoomIndex"
    :guest-index="editGuestIndex"
    :form-data="guestForm"
    :countries="countries as CountryResponse[]"
    @close="toggleGuestModal(false)"
    @submit="onModalSubmit"
  />

  <div class="mt-3" />
  <div
    v-for="(room, idx) in bookingDetails?.roomBookings"
    :key="idx"
    class="card mt-2 mb-4"
  >
    <div class="card-body">
      <div class="d-flex">
        <h5 class="card-title mr-4">
          {{ room.roomInfo.name }}
        </h5>
        <EditTableRowButton
          v-if="isEditableStatus"
          @edit="handleEditRoom(idx as number, room)"
          @delete="handleDeleteRoom(idx as number)"
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
                <th>Кол-во номеров</th>
                <td>{{ room.details.roomCount }}</td>
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
                <td>{{ room.details.discount }}</td>
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
                  <th>Цена за ночь</th>
                  <th>Итого</th>
                  <th>Подробный расчет</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Стоимость брутто</td>
                  <td>8 <span class="cur">$</span></td>
                  <td>22 <span class="cur">$</span></td>
                  <td>(8 <span class="cur">$</span> (брутто) * 1 (номер) * 1 (ночь)) + 4 <span class="cur">$</span> (ранний заезд) * 1 (номер) + 8 <span class="cur">$</span> (поздний выезд) * 1 (номер)</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="conditions">
            <span class="condition-item">Ранний заезд  - 4 <span class="cur">$</span></span>
            <span class="condition-item">Поздний выезд  - 8 <span class="cur">$</span></span>
          </div>
          <div class="d-flex flex-row justify-content-between w-100 mt-2">
            <strong>Итого: 22 <span class="cur">$</span></strong>
            <a href="#">Изменить цену номера</a>
          </div>
        </div>
        <div class="w-100 rounded shadow-lg p-4">
          <h6>Список гостей</h6>
          <a v-if="isEditableStatus" href="#" @click.prevent="handleAddRoomGuest(idx as number)">
            <i class="icon">add</i>
          </a>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>№</th>
                <th class="column-text">ФИО</th>
                <th class="column-text">Пол</th>
                <th class="column-text">Гражданство</th>
                <th />
              </tr>
            </thead>
            <tbody>
              <tr v-for="(guest, guestIdx) in room.guests" :key="guestIdx">
                <td>{{ guestIdx + 1 }}</td>
                <td>{{ guest.fullName }}</td>
                <td>{{ getCountryName(guest.countryId) }}</td>
                <td>{{ getGenderName(guest.gender) }}</td>
                <td class="column-edit">
                  <a v-if="isEditableStatus" href="#" @click.prevent="handleEditGuest(idx as number, guestIdx as number, guest)">
                    <i class="icon">edit</i>
                  </a>
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
