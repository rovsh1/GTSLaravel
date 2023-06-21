export interface City {
  id: number
  name: string
}

export interface Car {
  id: number
  mark: string
  model: string
  cities?: City[]
}
