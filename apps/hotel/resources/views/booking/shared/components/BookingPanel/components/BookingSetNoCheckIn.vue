<script setup lang="ts">
import { computed, reactive, ref } from 'vue'

import { useToggle } from '@vueuse/core'

import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { Currency } from '~api/models'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import SelectComponent from '~components/SelectComponent.vue'

import { useCurrencyStore } from '~stores/currency'

const penaltyStatuses: SelectOption[] = [
  { value: 0, label: 'Со штрафом' },
  { value: 1, label: 'Без штрафа' },
]

const modalForm = ref<HTMLFormElement>()

const [isOpenedNoCheckInModal, toggleModalNoCheckIn] = useToggle()

const bookingStore = useBookingStore()
const { getCurrencyByCodeChar } = useCurrencyStore()

const penaltyCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.supplierPrice.currency.value),
)

const canSetNoCheckIn = computed(() => bookingStore.availableActions?.canSetNoCheckIn)

const isUpdating = ref(false)

const formData = reactive({
  status: 0,
  penalty: 0,
})

const onModalClose = () => {
  toggleModalNoCheckIn(false)
}

const onModalOpen = () => {
  formData.status = 0
  formData.penalty = 0
  toggleModalNoCheckIn(true)
}

const onModalSubmit = async () => {
  if (!modalForm.value?.reportValidity()) {
    return
  }
  isUpdating.value = true
  const isUpdated = await bookingStore.setNoCheckIn(formData.status === 0 ? formData.penalty : null)
  if (isUpdated) {
    toggleModalNoCheckIn(false)
  }
  isUpdating.value = false
}

</script>

<template>
  <div v-if="canSetNoCheckIn" class="mt-2">
    <BootstrapButton label="Отметить как незаезд" @click="onModalOpen" />
    <BaseDialog
      :opened="isOpenedNoCheckInModal"
      :loading="isUpdating"
      @keydown.enter.prevent="onModalSubmit"
      @close="onModalClose"
    >
      <template #title>
        Подтверждение брони
      </template>
      <form ref="modalForm" class="row g-3">
        <div class="col-md-12">
          <SelectComponent
            v-if="isOpenedNoCheckInModal"
            :options="penaltyStatuses"
            required
            label="Статус"
            :disabled="isUpdating"
            :value="formData.status"
            @change="(value) => {
              formData.status = Number(value)
            }"
          />
        </div>
        <div v-if="formData.status === 0" class="col-md-12 field-required">
          <label for="penalty" class="col-form-label">Сумма штрафа в {{ penaltyCurrency?.code_char }}</label>
          <input
            id="penalty"
            v-model="formData.penalty"
            :disabled="isUpdating"
            min="1"
            required
            type="number"
            class="form-control"
          />
        </div>
      </form>
      <template #actions-end>
        <button class="btn btn-primary" type="button" :disabled="isUpdating" @click="onModalSubmit">
          Сохранить
        </button>
        <button class="btn btn-cancel" type="button" :disabled="isUpdating" @click="onModalClose">Отмена</button>
      </template>
    </BaseDialog>
  </div>
</template>
