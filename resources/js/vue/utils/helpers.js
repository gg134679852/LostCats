import axios from 'axios'
import Swal from 'sweetalert2'

const baseURL = 'http://127.0.0.1:8000/api'

export const apiHelper = axios.create({
  baseURL
})

export const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
})