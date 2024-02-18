type PluralFormRuOne = string // word form, like 'символ'
type PluralFormRuFew = string // word form, like 'символа'
type PluralFormRuMany = string // word form, like 'символов'

const pluralize = (value: number, one: PluralFormRuOne, few: PluralFormRuFew, many: PluralFormRuMany) => {
  if (value > 10 && Math.floor((value % 100) / 10) === 1) {
    return many
  }
  switch (value % 10) {
    case 1:
      return one
    case 2:
      return few
    case 3:
      return few
    case 4:
      return few
    default:
      return many
  }
}

export type PluralForms = string[]

export const pluralForm = (value: number, forms: PluralForms): string =>
  pluralize(value, forms[0], forms[1], forms[2])

export const plural = (value: number, forms: PluralForms): string =>
  `${value} ${pluralForm(value, forms)}`
