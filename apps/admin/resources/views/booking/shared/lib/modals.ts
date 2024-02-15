import axios from '~resources/js/api'
import { CancelReasonResponse } from '~resources/vue/api/cancel-reason'

import { CacheStorage } from '~cache/cache-storage'
import { TTLValues } from '~cache/enums'

import { ShowDialogResponse, ToggleCloseFunction, ToggleLoadingFunction } from '~helpers/confirm-dialog'

export interface ShowNotConfirmedReasonDialogResponse extends ShowDialogResponse {
  reason: string
}

export type CancelReason = CancelReasonResponse

export const showNotConfirmedReasonDialog = async (): Promise<ShowNotConfirmedReasonDialogResponse> => {
  const cancelReasons = await CacheStorage.remember('cancel-reasons', TTLValues.DAY, async () => {
    const cancelReasonsResponse = await axios.get('/cancel-reason/list')
    return cancelReasonsResponse?.data ? cancelReasonsResponse.data : null
  }) as CancelReasonResponse[]
  return new Promise((resolve): void => {
    let reasonDescription: string
    let $reasonSelectField: JQuery<HTMLElement> | null = null
    const $descriptionElement = $('<textarea />', {
      id: 'form_data_description',
      class: 'form-control',
    }).on('input', (e) => {
      reasonDescription = $(e.target).val() as string
    })
    const $reasonDescriptionField = $('<div />', { class: 'row form-field field-textarea field-description field-required' })
      .append('<label for="form_data_description" class="col-sm-5 col-form-label">Описание</label>')
      .append(
        $('<div />', { class: 'col-sm-7 d-flex align-items-center' }).append($descriptionElement),
      )
    const $selectElementWrapper = $('<div class="col-sm-7 d-flex align-items-center"></div>')
    const $selectElement = $('<select name="data[reason]" id="form_data_reason" class="form-select form-control" required></select>')
      .change((e) => {
        const $element = $(e.target)
        const hasDescription = $element.val() === ''
        if (!hasDescription) {
          $reasonSelectField?.removeClass('form-field')
          $element.attr('required', 'required')
          $descriptionElement.removeAttr('required')
          $reasonDescriptionField.hide()
          reasonDescription = $element.find('option:selected').text()
        } else {
          $reasonSelectField?.addClass('form-field')
          $element.removeAttr('required')
          $descriptionElement.attr('required', 'required')
          $reasonDescriptionField.show()
        }
      })
    $selectElement.append('<option value="">Другое</option>')
    cancelReasons?.forEach((reason) => {
      $selectElement.append(`<option value="${reason.id}">${reason.name}</option>`)
    })

    $selectElementWrapper.append($selectElement)
    $reasonSelectField = $('<div />', {
      class: 'row form-field field-enum field-type field-required',
    })
      .append('<label for="form_data_reason" class="col-sm-5 col-form-label">Выберите причину</label>')
      .append($selectElementWrapper)

    const $form = $('<form />', { method: 'post' })
      .append($reasonSelectField)
      .append($reasonDescriptionField)

    $selectElement.change()

    const getResolveCloseOptions = () => ({ result: false, reason: reasonDescription, toggleLoading: () => {}, toggleClose: () => {} })

    window.WindowDialog({
      title: 'Укажите причину',
      html: $form,
      buttons: [{ text: 'Сохранить', cls: 'btn btn-primary', handler: 'submit' }, 'cancel'],
      beforeSubmit: (form: any, closeHandler: ToggleCloseFunction, toggleLoading: ToggleLoadingFunction) => {
        resolve({ result: true, reason: reasonDescription, toggleLoading, toggleClose: closeHandler })
        return false
      },
      hide: () => {
        resolve(getResolveCloseOptions())
      },
      close: () => {
        resolve(getResolveCloseOptions())
      },
    })
  })
}

export interface ShowCancelFeeDialogResponse extends ShowDialogResponse {
  clientCancelFeeAmount: number
  cancelFeeAmount: number
}

export interface CancelFeeDialogOptions {
  cancelFeeAmount?: number
  withClientCancelFee?: boolean
  cancelFeeCurrencyLabel: string
  isRequired?: boolean
  clientCancelFeeCurrencyLabel?: string
}

export const showCancelFeeDialog = (options: CancelFeeDialogOptions): Promise<ShowCancelFeeDialogResponse> => new Promise((resolve): void => {
  let clientCancelFeeAmount: number = 0
  let cancelFeeAmount: number = options.cancelFeeAmount || 0
  let $clientReasonDescriptionField = null
  const isRequired = options.isRequired !== undefined ? options.isRequired : true
  const $form = $('<form />', { method: 'post' })

  const $descriptionElement = $('<input />', {
    id: 'form_data_penalty_net',
    class: 'form-control',
    type: 'number',
    min: 0,
    required: isRequired,
    value: cancelFeeAmount > 0 ? cancelFeeAmount : undefined,
  }).on('input', (e) => {
    cancelFeeAmount = Number($(e.target).val())
  })
  let fieldClasses = 'row form-field field-text field-value'
  if (isRequired) {
    fieldClasses += ' field-required'
  }
  const $reasonDescriptionField = $('<div />', { class: fieldClasses })
    .append(`<label for="form_data_penalty_net" class="${options.withClientCancelFee ? 'col-sm-12' : 'col-sm-5'} 
    col-form-label">Сумма штрафа в ${options.cancelFeeCurrencyLabel}</label>`)
    .append(
      $('<div />', { class: `${options.withClientCancelFee ? 'col-sm-12' : 'col-sm-7'} d-flex align-items-center` }).append($descriptionElement),
    )

  if (options.withClientCancelFee) {
    const $clientDescriptionElement = $('<input />', {
      id: 'form_data_penalty_gross',
      class: 'form-control',
      type: 'number',
      min: 0,
      required: true,
    }).on('input', (e) => {
      clientCancelFeeAmount = Number($(e.target).val())
    })
    $clientReasonDescriptionField = $('<div />', { class: 'row form-field field-text field-value field-required' })
      .append(`<label for="form_data_penalty_gross" class="${options.withClientCancelFee ? 'col-sm-12' : 'col-sm-5'}
       col-form-label">Сумма штрафа для клиента в ${options.clientCancelFeeCurrencyLabel}</label>`)
      .append(
        $('<div />', { class: `${options.withClientCancelFee ? 'col-sm-12' : 'col-sm-7'} d-flex align-items-center` }).append($clientDescriptionElement),
      )
    $form.append($clientReasonDescriptionField)
    $form.append($reasonDescriptionField)
  } else {
    $form.append($reasonDescriptionField)
  }

  const getResolveCloseOptions = () => ({ result: false, clientCancelFeeAmount, cancelFeeAmount, toggleLoading: () => {}, toggleClose: () => {} })

  window.WindowDialog({
    title: 'Укажите сумму штрафа',
    html: $form,
    buttons: [{ text: 'Сохранить', cls: 'btn btn-primary', handler: 'submit' }, 'cancel'],
    beforeSubmit: (form: any, closeHandler: ToggleCloseFunction, toggleLoading: ToggleLoadingFunction) => {
      resolve({ result: true, clientCancelFeeAmount, cancelFeeAmount, toggleLoading, toggleClose: closeHandler })
      return false
    },
    hide: () => {
      resolve(getResolveCloseOptions())
    },
    close: () => {
      resolve(getResolveCloseOptions())
    },
  })
})
