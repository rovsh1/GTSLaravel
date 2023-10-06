<script setup lang="ts">

import InlineSVG from 'vue-inline-svg'

import closeIcon from '@mdi/svg/svg/trash-can.svg'
import { MaybeRef } from '@vueuse/core'

import { TransferFormData } from '~resources/views/booking/lib/data-types'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'

const props = defineProps<{
  opened?: MaybeRef<boolean>
  formData?: Partial<TransferFormData>
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'clear'): void
  (event: 'submit'): void
}>()

</script>

<template>
  <div class="cars-items-list">
    <div class="cars-item mb-2">
      <div class="cars-item-field-model">
        <BootstrapSelectBase
          id="room_id"
          :options="[]"
          label="Модель"
          :value="0"
          required
          :disabled="false"
        />
      </div>
      <div class="cars-item-field-car-count">
        <div class="field-required">
          <label class="form-label" for="legal-name">Кол-во авто</label>
          <input
            id="legal-name"
            class="form-control"
            required
          >
        </div>
      </div>
      <div class="cars-item-field-passengers-count">
        <BootstrapSelectBase
          id="room_id"
          :options="[]"
          label="Кол-во пассажиров"
          :value="0"
          required
          :disabled="false"
        />
      </div>
      <div class="cars-item-field-baggage-count">
        <BootstrapSelectBase
          id="room_id"
          :options="[]"
          label="Кол-во багажа"
          :value="0"
          required
          :disabled="false"
        />
      </div>
      <div class="cars-item-actions">
        <InlineSVG :src="closeIcon" />
      </div>
    </div>
  </div>
  <div class="cars-items-action mt-3">
    <a href="#" @click.prevent="() => {}">
      <i class="icon">add</i> <span>Добавить модель авто</span>
    </a>
  </div>
</template>

<style lang="scss">
.cars-item {
  display: flex;
  align-items: end;

  & > div {
    margin-right: 10px;
  }

  & > div:last-child {
    margin-right: 0;
  }

  div,
  .field-required div {
    .form-label {
      font-size: 0.7rem
    }
  }

  .cars-item-field-model {
    width: 40%;
  }

  .cars-item-field-car-count {
    width: 20%;
  }

  .cars-item-actions {
    margin-bottom: 0.4rem;
    cursor: pointer;

    svg {
      width: auto;
      height: 1.2rem;
    }
  }
}

a {
  i,
  span {
    vertical-align: middle;
  }
}
</style>
