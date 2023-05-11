export type BootstrapSeverity =
  | 'primary'
  | 'secondary'
  | 'success'
  | 'danger'
  | 'warning'
  | 'info'
  | 'light'
  | 'dark'

export type SelectOption = {
  value: string | number | ''
  label: string
}
