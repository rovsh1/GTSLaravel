<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { HotelRoom } from '~api/hotel/room'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'

const { hotelID, rooms } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    hotelID: z.number(),
    rooms: z.array(z.any()),
  }),
)

console.log(hotelID)

const preparedRooms = computed<SelectOption[]>(() => rooms.map((room: HotelRoom) => ({ value: room.id, label: room.name })))

const [isShowModal, toggleModal] = useToggle()
const isFetching = ref<boolean>(false)

const modalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!modalForm.value?.reportValidity()) {
    return
  }
  isFetching.value = true

  //
  isFetching.value = false

  toggleModal(false)
}

</script>

<template>
  <BaseDialog
    :opened="isShowModal as boolean"
    :loading="isFetching"
    @close="toggleModal(false)"
  >
    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <BootstrapSelectBase
          id="room_id"
          :options="preparedRooms"
          label="Номер"
          value=""
          required
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="guests_count"
          :options="[]"
          label="Кол-во гостей"
          value=""
          required
        />
      </div>
      <div class="col-md-6">
        <label for="rooms_count" class="col-form-label">Кол-во номеров</label>
        <input id="rooms_count" type="number" class="form-control" required>
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="resident_type"
          label="Тип стоимости"
          :options="[]"
          value=""
          required
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="discount"
          label="Скидка"
          :options="[]"
          value=""
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="early_checkin"
          :options="[]"
          label="Ранний заезд"
          value=""
        />
      </div>
      <div class="col-md-6">
        <BootstrapSelectBase
          id="late_checkout"
          :options="[]"
          label="Поздний выезд"
          value=""
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="rate_id"
          :options="[]"
          label="Тариф"
          value=""
          required
        />
      </div>
      <div class="col-md-12">
        <label for="note" class=" col-form-label">Примечание</label>
        <textarea id="note" class="form-control" />
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="toggleModal(false)">Отмена</button>
    </template>
  </BaseDialog>

  <h5 class="mt-3">Номера</h5>
  <div class="card flex-grow-1 flex-basis-200 mt-1">
    <div class="card-body">
      <h5 class="card-title">
        Номер 1
      </h5>
      <table class="table-params">
        <tbody>
          <tr class="">
            <th>Статус</th>
            <td>Status</td>
          </tr>
          <tr class="">
            <th>Примечание</th>
            <td>-</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-4">
    <button type="button" class="btn btn-primary" @click="isShowModal = true">Добавить номер</button>
  </div>
</template>
