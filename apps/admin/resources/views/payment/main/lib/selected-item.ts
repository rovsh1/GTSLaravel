import { Ref, ref } from 'vue'

import { PaymentOrder } from '~api/payment/payment'

type PaymentOrderSelection = PaymentOrder & {
  distributedAmount?: number
}

interface SelectionState {
  selected: Ref<PaymentOrderSelection[]>
  isSelectedItem: (item: PaymentOrderSelection) => boolean
  toggleSelectItem: (item: PaymentOrderSelection) => void
}

export const createSelectionState = (): SelectionState => {
  const selected = ref<PaymentOrderSelection[]>([])

  const isSelectedItem = (item: PaymentOrderSelection) => selected.value.some((order) => order.id === item.id)

  const toggleSelectItem = (item: PaymentOrderSelection) => {
    if (!isSelectedItem(item)) {
      selected.value.push(item)
    } else {
      selected.value = selected.value.filter((element) => element.id !== item.id)
    }
  }

  return { selected, isSelectedItem, toggleSelectItem }
}
