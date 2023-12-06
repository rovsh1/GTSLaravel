import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, useAdminAPI } from '~api'
import { BaseOrder, OrderID } from '~api/order/models'
import { OrderVoucher } from '~api/order/voucher'

import { getNullableRef } from '~lib/vue'

export type Order = BaseOrder & { voucher: OrderVoucher | null }

export interface GetOrderPayload {
  orderID: OrderID
}

export interface UpdateOrderStatusPayload {
  orderID: OrderID
  status: number
  notConfirmedReason?: string
  cancelFeeAmount?: number
  clientCancelFeeAmount?: number
}

export interface UpdateNotePayload {
  orderID: OrderID
  note?: string
}

export interface UpdateManagerPayload {
  orderID: OrderID
  managerId: number | `${number}`
}

export interface UpdateOrderStatusResponse {
  isNotConfirmedReasonRequired: boolean
  isCancelFeeAmountRequired: boolean
}

export interface CopyOrderPayload {
  orderID: OrderID
}

export const useGetOrderAPI = (props: MaybeRef<GetOrderPayload>) =>
  useAdminAPI(props, ({ orderID }) => `/booking-order/${orderID}/get`)
    .get()
    .json<Order>()

export const updateOrderStatus = (props: MaybeRef<UpdateOrderStatusPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/status/update`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateOrderStatusPayload, any>(
        props,
        (payload: UpdateOrderStatusPayload): any => ({
          status: payload.status,
          not_confirmed_reason: payload.notConfirmedReason,
          net_penalty: payload.cancelFeeAmount,
          gross_penalty: payload.clientCancelFeeAmount,
        }),
      ),
    )), 'application/json')
    .json<UpdateOrderStatusResponse>()

export const updateNote = (props: MaybeRef<UpdateNotePayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/note`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateNotePayload, any>(
        props,
        (payload: UpdateNotePayload): any => ({
          note: payload.note,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updateManager = (props: MaybeRef<UpdateManagerPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/manager`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateManagerPayload, any>(
        props,
        (payload: UpdateManagerPayload): any => ({
          manager_id: payload.managerId,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const copyOrder = (props: MaybeRef<CopyOrderPayload>) => {
  const payload = unref(props)
  const form = document.createElement('form')
  document.body.appendChild(form)
  form.method = 'post'
  form.action = `/booking-order/${payload.orderID}/copy`
  form.submit()
}
