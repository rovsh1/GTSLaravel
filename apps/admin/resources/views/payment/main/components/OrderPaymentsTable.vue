<script setup lang="ts">
import { ref, watch } from 'vue'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import InlineIcon from '~components/InlineIcon.vue'

const props = withDefaults(defineProps<{
  paymentId: number | undefined
  orders: any[]
  loading: boolean
}>(), {

})

const localeOrders = ref<any[]>(props.orders)

const selectedOrders = ref<any[]>([])

const isSelectedItem = (item: any) => selectedOrders.value.includes(item)

const toogleSelectOrder = (item: any) => {
  if (!isSelectedItem(item)) {
    selectedOrders.value.push(item)
  } else {
    selectedOrders.value = selectedOrders.value.filter((element) => element !== item)
  }
}

watch(() => props.orders, (newValue) => {
  localeOrders.value = newValue
})

</script>

<template>
  <div class="d-flex align-items-stretch">
    <div>
      <p class="h6">Не включены в платеж:</p>
      <div class="height-fix height-fix--long">
        <table class="table table-bordered mb-0 w-100 table-sm">
          <thead class="table-light">
            <tr>
              <th scope="col">№ Заказа</th>
              <th class="column-text">Цена продажи</th>
              <th class="column-text">Валюта</th>
              <th class="column-text">Остаток</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="localeOrders.length">
              <tr
                v-for="order in localeOrders"
                :key="order"
                :class="{ 'table-active': isSelectedItem(order) }"
                @click="toogleSelectOrder(order)"
              >
                <td>{{ order }}</td>
                <td>{{ order }}</td>
                <td>{{ order }}</td>
                <td>{{ order }}</td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td class="text-center" colspan="4">Нет данных</td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
    <div class="d-flex flex-column justify-content-center align-items-center p-4">
      <div class="mb-2">
        <BootstrapButton
          label=""
          start-icon="keyboard_arrow_right"
          size="small"
          severity="secondary"
          variant="outline"
          :disabled="false"
          @click="() => { }"
        />
      </div>
      <div class="mb-4">
        <BootstrapButton
          label=""
          start-icon="keyboard_double_arrow_right"
          size="small"
          severity="secondary"
          variant="outline"
          :disabled="false"
          @click="() => { }"
        />
      </div>
      <div class="mb-2">
        <BootstrapButton
          label=""
          start-icon="keyboard_arrow_left"
          size="small"
          severity="secondary"
          variant="outline"
          :disabled="false"
          @click="() => { }"
        />
      </div>
      <div>
        <BootstrapButton
          label=""
          start-icon="keyboard_double_arrow_left"
          size="small"
          severity="secondary"
          variant="outline"
          :disabled="false"
          @click="() => { }"
        />
      </div>
    </div>
    <div class="d-flex flex-column justify-content-between">
      <div>
        <p class="h6">Включены в платеж:</p>
        <div class="height-fix">
          <table class="table table-bordered mb-0 w-100 table-sm">
            <thead class="table-light">
              <tr>
                <th scope="col">№ Заказа</th>
                <th scope="col" class="column-text">Цена продажи</th>
                <th class="column-text">Валюта</th>
                <th class="column-text">Сумма оплаты в валюте</th>
                <th class="column-text">Курс</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in 500" :key="order">
                <td>{{ order }}</td>
                <td>{{ order }}</td>
                <td>{{ order }}</td>
                <td>{{ order }}</td>
                <td />
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="mt-3 input-group--small">
        <label for="total-amount" class="form-label fs-7">Нераспределенная сумма:</label>
        <div class="input-group">
          <span id="basic-addon3" class="input-group-text">
            <InlineIcon icon="payments" />
          </span>
          <input id="total-amount" type="text" class="form-control text-right" disabled aria-describedby="basic-addon3">
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.height-fix {
  height: 21.875rem;
  overflow-y: auto;
  padding-right: 6px;

  table {
    border-collapse: separate;
    border-spacing: 0;

    thead {
      th {
        font-size: 0.8rem;
        font-weight: normal;
        position: sticky;
        top: 0;
        z-index: 1;
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;

        &:first-child {
          border-left: 1px solid #dee2e6;
        }
      }
    }

    tbody {
      td {
        cursor: pointer;
        border-bottom: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;

        &:first-child {
          border-left: 1px solid #dee2e6;
        }
      }
    }
  }
}

.height-fix--long {
  height: 26.875rem;
}

.input-group--small {
  font-size: 0.7rem;

  .input-group {
    width: 9.5rem;
  }

  label {
    margin-bottom: 0.2rem;
  }

  i {
    font-size: 1.2rem;
  }
}
</style>
