import axios from '~resources/js/app/api'
import bootBeds from '~resources/views/hotel/_services/room-editor'
import { Select2Option } from '~resources/views/hotel-booking/form/lib/types'

import '~resources/views/main'

$(() => {
  const switchInputToSelect = async (inputsIDs: string, urlForGetData: string): Promise<void> => {
    const IDsinputsIDsList = inputsIDs.split(',')
    if (IDsinputsIDsList.length < 1) return
    let roomsNameListData = []
    let roomsNameSelectOptions: Select2Option[] = []
    try {
      const roomsNameList = await axios.get(urlForGetData)
      roomsNameListData = roomsNameList && roomsNameList.data ? roomsNameList.data : []
    } catch (e) {
      roomsNameListData = []
    }
    roomsNameSelectOptions = roomsNameListData.map(
      (roomName: string) => ({ id: roomName, text: roomName }),
    )
    IDsinputsIDsList.forEach((inputID) => {
      const replaceInput = $(`#${inputID ? inputID.trim() : ''}`)
      if (!replaceInput) return

      const replaceInputAttrClass = replaceInput.attr('class')
      const replaceInputAttrName = replaceInput.attr('name')
      const replaceInputAttrDataLang = replaceInput.attr('data-lang')
      const replaceInputAttrRequired = replaceInput.attr('required')
      const replaceInputAttrValue = replaceInput.val() === undefined ? '' : replaceInput.val()?.toString().trim() as string
      const selectElement = $('<select>', {
        'id': inputID,
        'name': replaceInputAttrName,
        'style': 'width: 100%',
        'class': replaceInputAttrClass,
        'data-lang': replaceInputAttrDataLang,
        'required': replaceInputAttrRequired,
      })
      const parentElement = replaceInput.parent()
      replaceInput.remove()
      parentElement.append(selectElement)
      selectElement.select2({ data: roomsNameSelectOptions, tags: true, selectOnClose: false })
      const searchValueFromInput = roomsNameSelectOptions.filter((item) => item.text?.toLowerCase() === replaceInputAttrValue.toLowerCase())
      selectElement.val(searchValueFromInput[0] ? searchValueFromInput[0].id : '').trigger('change')
    })
  }

  switchInputToSelect('form_data_name_ru', '/hotels/rooms/names/ru/list')
  switchInputToSelect('form_data_name_en', '/hotels/rooms/names/en/list')
  switchInputToSelect('form_data_name_uz', '/hotels/rooms/names/uz/list')
  bootBeds($('#room-beds'))
})