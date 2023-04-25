function singularize(word: string): string {
  const endings: Record<string, string> = {
    ves: 'fe',
    ies: 'y',
    i: 'us',
    zes: 'ze',
    ses: 's',
    es: 'e',
    s: '',
  }
  return word.replace(
    new RegExp(`(${Object.keys(endings).join('|')})$`),
    (r) => endings[r],
  )
}

export const useUrlParams = (): Record<string, number> => {
  const regex = /\/([a-z]+)\/(\d+)/g

  const params:Record<string, number> = {}
  // eslint-disable-next-line no-constant-condition
  while (true) {
    const match = regex.exec(location.pathname)
    if (!match) {
      break
    }

    const paramName = singularize(match[1])
    params[paramName] = Number(match[2])
  }

  return params
}
