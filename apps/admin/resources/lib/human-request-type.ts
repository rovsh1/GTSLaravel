export const getHumanRequestType = (type: number): string => {
    let preparedType = 'изменение'
    if (type === 1) {
      preparedType = 'бронирование'
    }
    if (type === 3) {
      preparedType = 'отмену'
    }
    return preparedType
  }