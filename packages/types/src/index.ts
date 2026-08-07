export interface ApiResponse<TData = any> {
  success: boolean
  message?: string
  data?: TData
  errors?: Record<string, string[]>
  meta?: ApiPaginationMeta
  links?: ApiPaginationLinks
}

export interface ApiPaginationMeta {
  current_page: number
  from: number | null
  last_page: number
  path: string
  per_page: number
  to: number | null
  total: number
}

export interface ApiPaginationLinks {
  first: string
  last: string
  prev: string | null
  next: string | null
}
