import { Ref, ref } from 'vue'

import { PaymentOrder } from '~api/payment/payment'

interface SelectionState {
  selected: Ref<PaymentOrder[]>
  isSelectedItem: (item: PaymentOrder) => boolean
  toggleSelectItem: (item: PaymentOrder) => void
}

export const createSelectionState = (): SelectionState => {
  const selected = ref<PaymentOrder[]>([])

  const isSelectedItem = (item: PaymentOrder) => selected.value.some((order) => order.id === item.id)

  const toggleSelectItem = (item: PaymentOrder) => {
    if (!isSelectedItem(item)) {
      selected.value.push(item)
    } else {
      selected.value = selected.value.filter((element) => element.id !== item.id)
    }
  }

  return { selected, isSelectedItem, toggleSelectItem }
}
