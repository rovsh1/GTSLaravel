<script setup lang="ts">
import { ref, watch } from 'vue'

import { PaymentOrder, PaymentOrderPayload, PaymentPrice } from '~api/payment/payment'

import { formatPrice } from '~lib/price'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'

const props = withDefaults(defineProps<{
  remainingAmount: PaymentPrice | undefined
  waitingOrders: PaymentOrder[]
  orders: PaymentOrder[]
  loading: boolean
  disabled?: boolean
}>(), {
  disabled: false,
})

const emit = defineEmits<{
  (event: 'orders', value: PaymentOrderPayload[]): void
}>()

const localeWaitingOrders = ref<PaymentOrder[]>(props.waitingOrders)
const localeOrders = ref<PaymentOrder[]>(props.orders)
const localeRemainingAmount = ref<number | undefined>(props.remainingAmount?.value)

const selectedWaitingOrders = ref<PaymentOrder[]>([])
const selectedOrders = ref<PaymentOrder[]>([])

const isSelectedWaitingOrdersItem = (item: PaymentOrder) => selectedWaitingOrders.value.some((order) => order.id === item.id)

const isSelectedOrdersItem = (item: PaymentOrder) => selectedOrders.value.some((order) => order.id === item.id)

const toogleSelectWaitingOrder = (item: PaymentOrder) => {
  if (!isSelectedWaitingOrdersItem(item)) {
    selectedWaitingOrders.value.push(item)
  } else {
    selectedWaitingOrders.value = selectedWaitingOrders.value.filter((element) => element.id !== item.id)
  }
}

const toogleSelectOrder = (item: PaymentOrder) => {
  if (!isSelectedOrdersItem(item)) {
    selectedOrders.value.push(item)
  } else {
    selectedOrders.value = selectedOrders.value.filter((element) => element.id !== item.id)
  }
}

const getPaymentOrdersPayload = (): PaymentOrderPayload[] => localeOrders.value.map((order) => ({
  id: order.id,
  sum: order.clientPrice.value - order.remainingAmount.value,
}))

watch([() => props.orders, () => props.waitingOrders, () => props.remainingAmount], () => {
  localeWaitingOrders.value = props.waitingOrders
  localeOrders.value = props.orders
  localeRemainingAmount.value = props.remainingAmount?.value
})

const getIndexToDelete = (array: PaymentOrder[], removedItem: PaymentOrder) => {
  const indexToDelete = array.findIndex((item) => item.id === removedItem.id)
  return indexToDelete
}

const moveOrderWaitingToOrders = async (allOrders?: boolean) => {
  if (allOrders) {
    selectedWaitingOrders.value = localeWaitingOrders.value
  }
  if (!selectedWaitingOrders.value.length
  || localeRemainingAmount.value === undefined || localeRemainingAmount.value === 0) {
    selectedWaitingOrders.value = []
    return
  }
  selectedWaitingOrders.value.forEach((selectedOrder) => {
    const source = selectedOrder
    const subsctractSum = localeRemainingAmount.value as number - selectedOrder.remainingAmount.value
    const existLocaleOrder = localeOrders.value.find((order) => order.id === source.id)
    if (subsctractSum >= 0) {
      if (existLocaleOrder) {
        existLocaleOrder.remainingAmount.value = 0
      } else {
        source.remainingAmount.value = 0
        localeOrders.value.push(selectedOrder)
      }
      localeWaitingOrders.value.splice(getIndexToDelete(localeWaitingOrders.value, selectedOrder))
      localeRemainingAmount.value = subsctractSum
    } else {
      if (existLocaleOrder) {
        existLocaleOrder.remainingAmount.value = Math.abs(subsctractSum)
      } else {
        source.remainingAmount.value = Math.abs(subsctractSum)
        localeOrders.value.push(selectedOrder)
      }
      const localeWaitingOrder = localeWaitingOrders.value.find((order) => order.id === source.id)
      if (localeWaitingOrder) {
        localeWaitingOrder.remainingAmount.value = source.remainingAmount.value
      }
      localeRemainingAmount.value = 0
    }
  })
  selectedWaitingOrders.value = []
  emit('orders', getPaymentOrdersPayload())
}

const moveOrdersToOrderWaiting = async (allOrders?: boolean) => {
  if (allOrders) {
    selectedOrders.value = localeOrders.value
  }
  if (!selectedOrders.value.length
  || localeRemainingAmount.value === undefined || localeRemainingAmount.value === props.remainingAmount?.value) {
    selectedOrders.value = []
    return
  }
  selectedOrders.value.forEach((selectedOrder) => {
    const source = selectedOrder
    const addedSum = selectedOrder.clientPrice.value - selectedOrder.remainingAmount.value
    const existLocaleWaitingOrder = localeWaitingOrders.value.find((order) => order.id === source.id)
    if (existLocaleWaitingOrder) {
      existLocaleWaitingOrder.remainingAmount.value = existLocaleWaitingOrder.clientPrice.value
    } else {
      source.remainingAmount.value = selectedOrder.clientPrice.value
      localeWaitingOrders.value.push(selectedOrder)
    }
    localeOrders.value.splice(getIndexToDelete(localeOrders.value, selectedOrder))
    localeRemainingAmount.value = localeRemainingAmount.value as number + addedSum
  })
  selectedOrders.value = []
  emit('orders', getPaymentOrdersPayload())
}

</script>

<template>
  <div class="d-flex align-items-stretch">
    <div>
      <p class="h6">Не включены в платеж:</p>
      <div class="height-fix height-fix--long" :class="{ 'height-fix--position': !loading }">
        <table class="table table-bordered mb-0 w-100 table-sm">
          <thead class="table-light">
            <tr>
              <th scope="col">№ Заказа</th>
              <th class="column-text">Цена продажи</th>
              <th class="column-text">Остаток</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="localeWaitingOrders.length">
              <tr
                v-for="waitingOrder in localeWaitingOrders"
                :key="waitingOrder.id"
                :class="{ 'table-active': isSelectedWaitingOrdersItem(waitingOrder) }"
                @click="toogleSelectWaitingOrder(waitingOrder)"
              >
                <td>{{ waitingOrder.id }}</td>
                <td>{{ formatPrice(waitingOrder.clientPrice.value, waitingOrder.clientPrice.currency.value) }}</td>
                <td>{{ formatPrice(waitingOrder.remainingAmount.value, waitingOrder.remainingAmount.currency.value) }}</td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td class="text-center" colspan="3">Нет данных</td>
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
          :disabled="disabled"
          @click="() => moveOrderWaitingToOrders()"
        />
      </div>
      <div class="mb-4">
        <BootstrapButton
          label=""
          start-icon="keyboard_double_arrow_right"
          size="small"
          severity="secondary"
          variant="outline"
          :disabled="disabled"
          @click="() => moveOrderWaitingToOrders(true)"
        />
      </div>
      <div class="mb-2">
        <BootstrapButton
          label=""
          start-icon="keyboard_arrow_left"
          size="small"
          severity="secondary"
          variant="outline"
          :disabled="disabled"
          @click="() => moveOrdersToOrderWaiting()"
        />
      </div>
      <div>
        <BootstrapButton
          label=""
          start-icon="keyboard_double_arrow_left"
          size="small"
          severity="secondary"
          variant="outline"
          :disabled="disabled"
          @click="() => moveOrdersToOrderWaiting(true)"
        />
      </div>
    </div>
    <div class="d-flex flex-column justify-content-between">
      <div>
        <p class="h6">Включены в платеж:</p>
        <div class="height-fix" :class="{ 'height-fix--position': !loading }">
          <table class="table table-bordered mb-0 w-100 table-sm">
            <thead class="table-light">
              <tr>
                <th scope="col">№ Заказа</th>
                <th class="column-text">Цена продажи</th>
                <th class="column-text">Остаток</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="localeOrders.length">
                <tr
                  v-for="order in localeOrders"
                  :key="order.id"
                  :class="{ 'table-active': isSelectedOrdersItem(order) }"
                  @click="toogleSelectOrder(order)"
                >
                  <td>{{ order.id }}</td>
                  <td>{{ formatPrice(order.clientPrice.value, order.clientPrice.currency.value) }}</td>
                  <td>{{ formatPrice(order.remainingAmount.value, order.remainingAmount.currency.value) }}</td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td class="text-center" colspan="3">Нет данных</td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
      <div class="mt-3 input-group--small">
        <label for="total-amount" class="form-label fs-7">Нераспределенная сумма:</label>
        <div class="input-group">
          <span id="basic-addon3" class="input-group-text">
            {{ remainingAmount?.currency.value }}
          </span>
          <input
            id="total-amount"
            type="text"
            :value="formatPrice(localeRemainingAmount)"
            class="form-control text-right"
            disabled
            aria-describedby="basic-addon3"
          >
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.height-fix--position table thead th {
  position: sticky;
  top: 0;
  z-index: 1;
}

.height-fix {
  height: 21.875rem;
  min-width: 17rem;
  overflow-y: auto;
  padding-right: 6px;

  table {
    border-collapse: separate;
    border-spacing: 0;

    thead {
      th {
        font-size: 0.8rem;
        font-weight: normal;
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
