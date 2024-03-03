<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'

import { formatPrice } from 'gts-common/helpers/price'
import BootstrapButton from 'gts-components/Bootstrap/BootstrapButton/BootstrapButton'

import { createSelectionState } from '~resources/views/client-payment/main/lib/selected-item'

import { PaymentInfo, PaymentOrder, PaymentOrderPayload } from '~api/payment/payment'

type PaymentOrderDistributed = PaymentOrder & {
  distributedAmount: number
}

const props = withDefaults(defineProps<{
  waitingOrders: PaymentOrder[]
  distributedOrders: PaymentOrder[]
  paymentInfo: PaymentInfo | null
  loading: boolean
  clearSelectedOrders?: boolean
  disabled?: boolean
}>(), {
  disabled: false,
  clearSelectedOrders: false,
})

const emit = defineEmits<{
  (event: 'orders', value: PaymentOrderPayload[]): void
}>()

const setDistributedOrders = () => {
  const distributedOrders: PaymentOrderDistributed[] = []
  props.distributedOrders.forEach((distributedOrder) => {
    const source = distributedOrder
    const distributedAmount = props.paymentInfo
      ?.landings?.find((landing) => landing.orderId === distributedOrder.id)?.sum || 0
    distributedOrders.push({ ...source, distributedAmount })
  })
  return distributedOrders
}

const localeWaitingOrders = ref<PaymentOrder[]>([])
const localeDistributedOrders = ref<PaymentOrderDistributed[]>([])
const localeRemainingAmount = ref<number | undefined>()
const localePaymentTotalAmount = ref<number | undefined>()

const waitingOrdersSelection = createSelectionState()
const distributedOrdersSelection = createSelectionState()

const getPaymentOrdersPayload = (): PaymentOrderPayload[] => localeDistributedOrders.value.map((order) => ({
  id: order.id,
  sum: order.distributedAmount,
}))

const resetLocaleVariables = () => {
  localeWaitingOrders.value = props.waitingOrders
  localeRemainingAmount.value = props.paymentInfo?.remainingAmount?.value
  localePaymentTotalAmount.value = props.paymentInfo?.totalAmount.value
  localeDistributedOrders.value = setDistributedOrders()
}

watch([() => props.distributedOrders, () => props.waitingOrders, () => props.paymentInfo], () => {
  resetLocaleVariables()
  emit('orders', getPaymentOrdersPayload())
})

watch(() => props.clearSelectedOrders, (value) => {
  if (value) {
    distributedOrdersSelection.selected.value = []
    waitingOrdersSelection.selected.value = []
  }
})

const getIndexToDelete = (array: PaymentOrder[], removedItem: PaymentOrder) => {
  const indexToDelete = array.findIndex((item) => item.id === removedItem.id)
  return indexToDelete
}

const moveOrderWaitingToOrders = (allOrders?: boolean) => {
  if (localeRemainingAmount.value === undefined || localeRemainingAmount.value === 0) {
    waitingOrdersSelection.selected.value = []
    return
  }
  if (localeWaitingOrders.value.length && !waitingOrdersSelection.selected.value.length) {
    if (allOrders) {
      waitingOrdersSelection.selected.value = [...localeWaitingOrders.value]
    } else {
      waitingOrdersSelection.selected.value = [[...localeWaitingOrders.value][0]]
    }
  }
  waitingOrdersSelection.selected.value.forEach((selectedOrder) => {
    const remainingAmount = localeRemainingAmount.value as number
    if (remainingAmount <= 0) return
    const selectedWaitingOrder = selectedOrder
    const subtractSum = remainingAmount - selectedWaitingOrder.remainingAmount.value
    const existLocaleDistributedOrder = localeDistributedOrders.value.find((order) => order.id === selectedWaitingOrder.id)
    if (subtractSum >= 0) {
      if (existLocaleDistributedOrder) {
        existLocaleDistributedOrder.distributedAmount += selectedWaitingOrder.remainingAmount.value
        existLocaleDistributedOrder.remainingAmount.value = 0
      } else {
        const localeDistributedAmount = selectedWaitingOrder.remainingAmount.value
        selectedWaitingOrder.remainingAmount.value = 0
        localeDistributedOrders.value.push({ ...selectedWaitingOrder, distributedAmount: localeDistributedAmount })
      }
      localeWaitingOrders.value.splice(getIndexToDelete(localeWaitingOrders.value, selectedWaitingOrder), 1)
      localeRemainingAmount.value = subtractSum
    } else {
      if (existLocaleDistributedOrder) {
        existLocaleDistributedOrder.remainingAmount.value = Math.abs(subtractSum)
        existLocaleDistributedOrder.distributedAmount += selectedWaitingOrder.remainingAmount.value - Math.abs(subtractSum)
      } else {
        selectedWaitingOrder.remainingAmount.value = Math.abs(subtractSum)
        localeDistributedOrders.value.push({ ...selectedWaitingOrder, distributedAmount: remainingAmount })
      }
      const localeWaitingOrder = localeWaitingOrders.value.find((order) => order.id === selectedWaitingOrder.id)
      if (localeWaitingOrder) {
        localeWaitingOrder.remainingAmount.value = Math.abs(subtractSum)
      }
      localeRemainingAmount.value = 0
    }
  })
  waitingOrdersSelection.selected.value = []
  emit('orders', getPaymentOrdersPayload())
}

const moveOrdersToOrderWaiting = (allOrders?: boolean) => {
  if (localeRemainingAmount.value === undefined) {
    distributedOrdersSelection.selected.value = []
    return
  }
  if (localeDistributedOrders.value.length && !distributedOrdersSelection.selected.value.length) {
    if (allOrders) {
      distributedOrdersSelection.selected.value = [...localeDistributedOrders.value]
    } else {
      distributedOrdersSelection.selected.value = [[...localeDistributedOrders.value][0]]
    }
  }
  distributedOrdersSelection.selected.value.forEach((selectedOrder) => {
    const selectedDistributedOrder = selectedOrder
    const addedSum = selectedDistributedOrder.distributedAmount as number
    const existLocaleWaitingOrder = localeWaitingOrders.value.find((order) => order.id === selectedDistributedOrder.id)
    if (existLocaleWaitingOrder) {
      existLocaleWaitingOrder.remainingAmount.value += addedSum
    } else {
      selectedDistributedOrder.remainingAmount.value = selectedDistributedOrder.distributedAmount as number
      localeWaitingOrders.value.push(selectedOrder)
    }
    localeDistributedOrders.value.splice(getIndexToDelete(localeDistributedOrders.value, selectedDistributedOrder), 1)
    const calculatedLocaleRemainingAmount = localeRemainingAmount.value as number + addedSum
    if (localePaymentTotalAmount.value && calculatedLocaleRemainingAmount > localePaymentTotalAmount.value) {
      localeRemainingAmount.value = localePaymentTotalAmount.value
    } else {
      localeRemainingAmount.value = calculatedLocaleRemainingAmount
    }
  })
  distributedOrdersSelection.selected.value = []
  emit('orders', getPaymentOrdersPayload())
}

const getClientPrice = (order: PaymentOrder) => order.clientPenalty?.value || order.clientPrice.value

onMounted(() => {
  resetLocaleVariables()
  emit('orders', getPaymentOrdersPayload())
})

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
              <th class="column-text">Распределено по заказу</th>
              <th class="column-text">Остаток</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="localeWaitingOrders.length">
              <tr
                v-for="waitingOrder in localeWaitingOrders"
                :key="waitingOrder.id"
                :class="{ 'table-active': waitingOrdersSelection.isSelectedItem(waitingOrder) }"
                @click="() => {
                  distributedOrdersSelection.selected.value = []
                  waitingOrdersSelection.toggleSelectItem(waitingOrder)
                }"
              >
                <td>{{ waitingOrder.id }}</td>
                <td>{{ formatPrice(getClientPrice(waitingOrder), waitingOrder.clientPrice.currency.value) }}</td>
                <td>
                  {{ formatPrice((getClientPrice(waitingOrder)
                    - waitingOrder.remainingAmount.value), waitingOrder.clientPrice.currency.value) }}
                </td>
                <td>
                  {{ formatPrice(waitingOrder.remainingAmount.value, waitingOrder.remainingAmount.currency.value) }}
                </td>
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
                <th class="column-text">Распределено по заказу</th>
                <th class="column-text">Распределено из оплаты</th>
                <th class="column-text">Остаток</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="localeDistributedOrders.length">
                <tr
                  v-for="order in localeDistributedOrders"
                  :key="order.id"
                  :class="{ 'table-active': distributedOrdersSelection.isSelectedItem(order) }"
                  @click="() => {
                    waitingOrdersSelection.selected.value = []
                    distributedOrdersSelection.toggleSelectItem(order)
                  }"
                >
                  <td>{{ order.id }}</td>
                  <td>{{ formatPrice(getClientPrice(order), order.clientPrice.currency.value) }}</td>
                  <td>
                    {{ formatPrice((getClientPrice(order) - order.remainingAmount.value),
                                   order.clientPrice.currency.value) }}
                  </td>
                  <td>
                    {{ formatPrice((order.distributedAmount), order.payedAmount.currency.value) }}
                  </td>
                  <td>{{ formatPrice(order.remainingAmount.value, order.remainingAmount.currency.value) }}</td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td class="text-center" colspan="5">Нет данных</td>
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
            {{ paymentInfo?.remainingAmount?.currency.value }}
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
  overflow-y: auto;
  min-width: 17rem;
  height: 21.875rem;
  padding-right: 6px;

  table {
    border-spacing: 0;
    border-collapse: separate;

    thead {
      th {
        border-top: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        font-weight: normal;
        font-size: 0.8rem;

        &:first-child {
          border-left: 1px solid #dee2e6;
        }
      }
    }

    tbody {
      td {
        border-right: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        cursor: pointer;

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
