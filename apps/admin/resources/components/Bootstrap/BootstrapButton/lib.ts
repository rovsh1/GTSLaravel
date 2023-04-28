import { BootstrapSeverity } from '~resources/components/Bootstrap/lib'

export type ButtonType = 'auto' | 'submit' | 'reset'

export const buttonType: Record<ButtonType, ButtonType> = {
  auto: 'auto',
  submit: 'submit',
  reset: 'reset',
}

export type ButtonVariant = 'filled' | 'outline'

export const buttonVariant: Record<ButtonVariant, ButtonVariant> = {
  filled: 'filled',
  outline: 'outline',
}

export type ButtonSeverity = BootstrapSeverity | 'link'

export const buttonSeverity: Record<ButtonSeverity, ButtonSeverity> = {
  primary: 'primary',
  secondary: 'secondary',
  success: 'success',
  danger: 'danger',
  warning: 'warning',
  info: 'info',
  light: 'light',
  dark: 'dark',
  link: 'link',
}

export type ButtonSize = 'default' | 'large' | 'small'

export const buttonSize: Record<ButtonSize, ButtonSize> = {
  default: 'default',
  large: 'large',
  small: 'small',
}
