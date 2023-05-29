<script setup lang="ts">

import { ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import GuestModal from '~resources/views/hotel-booking/show/components/GuestModal.vue'
import RoomModal from '~resources/views/hotel-booking/show/components/RoomModal.vue'
import { getGenderName, getRoomStatusName } from '~resources/views/hotel-booking/show/constants'
import { GuestFormData, RoomFormData } from '~resources/views/hotel-booking/show/form'

import { HotelBookingDetailsRoom, HotelBookingGuest, useBookingHotelDetailsAPI } from '~api/booking/details'
import { deleteBookingRoom } from '~api/booking/rooms'
import { CountryResponse, useCountrySearchAPI } from '~api/country'
import { HotelRate, useHotelRatesAPI } from '~api/hotel/price-rate'
import { HotelRoomResponse } from '~api/hotel/room'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

const [isShowRoomModal, toggleRoomModal] = useToggle()
const [isShowGuestModal, toggleGuestModal] = useToggle()

const { bookingID, hotelID, hotelRooms } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    hotelID: z.number(),
    bookingID: z.number(),
    // @todo не понял как указать строгий тип
    hotelRooms: z.array(z.any()),
  }),
)

const { execute: fetchDetails, data: bookingDetails } = useBookingHotelDetailsAPI({ bookingID })
fetchDetails()

const { execute: fetchPriceRates, data: priceRates } = useHotelRatesAPI({ hotelID })
fetchPriceRates()

const { data: countries, execute: fetchCountries } = useCountrySearchAPI()
fetchCountries()

const onModalSubmit = () => {
  toggleRoomModal(false)
  toggleGuestModal(false)
  fetchDetails()
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

const handleEditRoom = (roomIndex: number, room: HotelBookingDetailsRoom): void => {
  editRoomIndex.value = roomIndex
  roomForm.value = room
  roomForm.value.residentType = room.isResident ? 1 : 0
  roomForm.value.note = room.guestNote
  toggleRoomModal()
}

const handleDeleteRoom = async (roomIndex: number): Promise<void> => {
  const { result: isApproved, toggleLoading, toggleClose } = await showConfirmDialog('Удалить номер?')
  if (isApproved) {
    toggleLoading()
    await deleteBookingRoom({ bookingID, roomIndex })
    await fetchDetails()
    toggleClose()
  }
}

const getRoomName = (id: number): string | undefined => {
  const room = hotelRooms.find((roomData: HotelRoomResponse) => roomData.id === id)
  if (!room) {
    return undefined
  }

  return `${room.name} (${room.custom_name})`
}

</script>

<template>
  <RoomModal
    :opened="isShowRoomModal"
    :form-data="roomForm"
    :room-index="editRoomIndex"
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

  <h5 class="mt-3">Номера</h5>
  <div
    v-for="(room, idx) in bookingDetails?.rooms"
    :key="idx"
    class="card mt-2 mb-4"
  >
    <div class="card-body">
      <div class="d-flex">
        <h5 class="card-title mr-4">
          {{ getRoomName(room.id) }}
        </h5>
        <EditTableRowButton
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
                <td>{{ room.roomCount }}</td>
              </tr>
              <tr>
                <th>Тип стоимости</th>
                <td>{{ room.isResident ? 'Резидент' : 'Не резидент' }}</td>
              </tr>
              <tr>
                <th>Тариф</th>
                <td>{{ getPriceRateName(room.rateId) }}</td>
              </tr>
              <tr>
                <th>Скидка</th>
                <td>{{ room.discount }}</td>
              </tr>
              <tr>
                <th>Время заезда</th>
                <td>-</td>
              </tr>
              <tr>
                <th>Время выезда</th>
                <td>-</td>
              </tr>
              <tr>
                <th>Примечание (запрос в отель, ваучер)</th>
                <td>{{ room.guestNote }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-100 rounded shadow-lg p-4">
          <h6>Список гостей</h6>
          <a href="#" @click.prevent="handleAddRoomGuest(idx as number)">
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
                  <a href="#" @click.prevent="handleEditGuest(idx as number, guestIdx as number, guest)">
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
    <button type="button" class="btn btn-primary" @click="handleAddRoom">Добавить номер</button>
  </div>
</template>
