<script setup lang="ts">

import { computed, ref } from 'vue'

import { ExternalNumberTypeEnum, externalNumberTypeOptions } from '~resources/views/hotel-booking/show/constants'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'

import StatusSelect from './components/StatusSelect.vue'

const status = ref<number>(0)
const externalNumberType = ref<number>()
const externalNumber = ref<string>()
const isNeedShowExternalNumber = computed<boolean>(
  () => externalNumberType.value === ExternalNumberTypeEnum.HotelBookingNumber
    || externalNumberType.value === ExternalNumberTypeEnum.FullName,
)

</script>

<template>
  <div class="d-flex flex-wrap flex-grow-1 gap-2">
    <StatusSelect v-model="status" />
    <a href="#" class="btn-log">История изменений</a>
    <div class="float-end">
      Общая сумма: <strong>0 <span class="cur">сўм</span></strong>
    </div>
  </div>

  <div class="mt-4">
    <h6>Тип номера подтверждения бронирования</h6>
    <hr>
    <div class="d-flex align-content-around">
      <div>
        <BootstrapSelectBase
          id="external_number_type"
          disabled-placeholder="Номер подтверждения брони в отеля"
          :value="externalNumberType as number"
          :options="externalNumberTypeOptions"
          @input="value => externalNumberType = value as number"
        />
      </div>
      <div class="d-flex ml-2">
        <div class="external-number-wrapper">
          <input
            v-if="isNeedShowExternalNumber"
            v-model="externalNumber"
            class="form-control"
            name="external_number"
            type="text"
            placeholder="№ брони"
          >
          <div class="invalid-feedback">
            Пожалуйста, заполните номер брони.
          </div>
        </div>
        <div class="ml-2">
          <a class="btn btn-primary">Сохранить</a>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <h6>Финансовая стоимость брони</h6>
    <hr>
  </div>
</template>

<style scoped lang="scss">
.ml-2 {
  margin-left: 0.5rem;
}
</style>
