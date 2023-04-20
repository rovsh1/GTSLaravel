const initUsabilitiesModal = () => {
  (function (list) {
    const rooms: any[] = JSON.parse($('input[name="_hotel-rooms"]').val() as string)
    const getRadioHtml = function (id: number) {
      // eslint-disable-next-line no-underscore-dangle
      const radioHTML = function (label: string, value: string) {
        const name = `f_hu_r_${id}`
        // eslint-disable-next-line no-underscore-dangle
        const htmlID = `${name}_${value}`
        let html = '<div class="radio">'
        html += `<input type="radio" id="${htmlID}" name="${name}" value="${value}" />`
        html += `<label for="${htmlID}">${label}</label>`
        html += '</div>'
        return html
      }

      let html = '<div class="radio-wrapper">'
      html += radioHTML('Все номера', 'all')
      html += radioHTML('Выбранные номера', 'selected')
      html += '</div>'

      return html
    }
    const getRoomsHtml = function (id: number) {
      const roomHTML = function (roomId: number, label: string) {
        const name = `data[usabilities][${id}][${roomId}]`
        const htmlID = `f_hu_${id}_${roomId}`
        let html = '<div class="radio">'
        html += `<label for="${htmlID}">${label}</label>`
        html += `<input data-id="${roomId}" type="checkbox" id="${htmlID}" name="${name}" value="1" />`
        html += '</div>'
        return html
      }

      let html = '<div class="rooms-wrapper">'
      for (let i = 0; i < rooms.length; i++) {
        html += roomHTML(rooms[i].id, `${rooms[i].name} (${rooms[i].custom_name || ''})`)
      }
      html += '</div>'

      return html
    }

    const initItemRooms = function (input: JQuery<HTMLElement>) {
      const li = input.parent()
      li.append(getRoomsHtml(li.data('id')))
    }
    const initItemRadio = function (input: JQuery<HTMLElement>, checked?: string) {
      const li = input.parent()
      li.append(getRadioHtml(li.data('id')))

      const radio = li.find('div.radio-wrapper input')

      radio.change(function () {
        if (!$(this)
          .is(':checked')) {
          return
        }

        if ($(this)
          .val() === 'all') {
          li.find('div.rooms-wrapper')
            .remove()
        } else {
          initItemRooms(input)
        }
      })

      radio[checked === 'selected' ? 'last' : 'first']()
        .prop('checked', true)
        .change()
    }

    list.find('input')
      .change(function () {
        const input = $(this)
        const li = input.parent()
        if (input.is(':checked')) {
          initItemRadio(input)
        } else {
          li.find('div.radio-wrapper')
            .remove()
          li.find('div.rooms-wrapper')
            .remove()
        }
      });

    (function () {
      const selected = JSON.parse($('input[name="_hotel-usabilities"]').val() as string)

      const hasSel = function (usabilityId: number) {
        for (let i = 0, l = selected.length; i < l; i++) {
          if (selected[i].usability_id === usabilityId && selected[i].room_id !== null) {
            return true
          }
        }
        return false
      }
      const isSel = function (usabilityId: number, roomId: number) {
        for (let i = 0, l = selected.length; i < l; i++) {
          if (selected[i].usability_id === usabilityId && selected[i].room_id === roomId) {
            return true
          }
        }
        return false
      }
      list.find('input:checked')
        .each(function () {
          const input = $(this)
          const li = input.parent()
          const id = li.data('id')
          const f = hasSel(id)
          initItemRadio(input, f ? 'selected' : 'all')
          if (!f) {
            return
          }
          li.find('div.rooms-wrapper input')
            .each(function () {
              if (isSel(id, $(this)
                .data('id'))) {
                $(this)
                  .prop('checked', true)
              }
            })
        })
    }())
  }($('#hotel-usabilities')))
}

export default initUsabilitiesModal
