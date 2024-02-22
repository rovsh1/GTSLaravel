import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { getNullableRef } from '~resources/vue/vue'

import { BaseResponse, useAdminAPI } from '~api'
import { BaseOrder, OrderID } from '~api/order/models'
import { OrderVoucher } from '~api/order/voucher'

export type Order = BaseOrder & { voucher: OrderVoucher | null }

export interface GetOrderPayload {
  orderID: OrderID
}

export interface UpdateOrderStatusPayload {
  orderID: OrderID
  status: number
  refundFee?: number
}

export interface UpdateNotePayload {
  orderID: OrderID
  note?: string
}

export interface UpdateExternalIdPayload {
  orderID: OrderID
  externalId?: string
}

export interface UpdatePenaltyPayload {
  orderID: OrderID
  clientPenalty?: number
}

export interface UpdateManagerPayload {
  orderID: OrderID
  managerId: number | `${number}`
}

export interface UpdateOrderStatusResponse {
  isRefundFeeAmountRequired: boolean
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
          refund_fee: payload.refundFee,
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

export const updateExternalId = (props: MaybeRef<UpdateExternalIdPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/external-id`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdateExternalIdPayload, any>(
        props,
        (payload: UpdateExternalIdPayload): any => ({
          externalId: payload.externalId,
        }),
      ),
    )), 'application/json')
    .json<BaseResponse>()

export const updatePenalty = (props: MaybeRef<UpdatePenaltyPayload>) =>
  useAdminAPI(
    props,
    ({ orderID }) => `/booking-order/${orderID}/penalty`,
    { immediate: true },
  )
    .put(computed<string>(() => JSON.stringify(
      getNullableRef<UpdatePenaltyPayload, any>(
        props,
        (payload: UpdatePenaltyPayload): any => ({
          clientPenalty: payload.clientPenalty,
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
