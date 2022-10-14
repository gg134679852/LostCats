import axios from 'axios'
import Swal from 'sweetalert2'

const baseURL = process.env.APP_URL

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
export const axiosHelper = axios.create({
  baseURL,
  headers: { withCredentials: true }
})

export const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
})
